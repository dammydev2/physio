ne in most other cases
            except for 1xx or 5xx responses where it MAY do so.

            Anyway, a client that received a message without a "Date" header MUST add it.
        */
        if (!$response->headers->has('Date')) {
            $response->setDate(\DateTime::createFromFormat('U', time()));
        }

        $this->processResponseBody($request, $response);

        if ($this->isPrivateRequest($request) && !$response->headers->hasCacheControlDirective('public')) {
            $response->setPrivate();
        } elseif ($this->options['default_ttl'] > 0 && null === $response->getTtl() && !$response->headers->getCacheControlDirective('must-revalidate')) {
            $response->setTtl($this->options['default_ttl']);
        }

        return $response;
    }

    /**
     * Checks whether the cache entry is "fresh enough" to satisfy the Request.
     *
     * @return bool true if the cache entry if fresh enough, false otherwise
     */
    protected function isFreshEnough(Request $request, Response $entry)
    {
        if (!$entry->isFresh()) {
            return $this->lock($request, $entry);
        }

        if ($this->options['allow_revalidate'] && null !== $maxAge = $request->headers->getCacheControlDirective('max-age')) {
            return $maxAge > 0 && $maxAge >= $entry->getAge();
        }

        return true;
    }

    /**
     * Locks a Request during the call to the backend.
     *
     * @return bool true if the cache entry can be returned even if it is staled, false otherwise
     */
    protected function lock(Request $request, Response $entry)
    {
        // try to acquire a lock to call the backend
        $lock = $this->store->lock($request);

        if (true === $lock) {
            // we have the lock, call the backend
            return false;
        }

        // there is already another process calling the backend

        // May we serve a stale response?
        if ($this->mayServeStaleWhileRevalidate($entry)) {
            $this->record($request, 'stale-while-revalidate');

            return true;
        }

        // wait for the lock to be released
        if ($this->waitForLock($request)) {
            // replace the current entry with the fresh one
            $new = $this->lookup($request);
            $entry->headers = $new->headers;
            $entry->setContent($new->getContent());
            $entry->setStatusCode($new->getStatusCode());
            $entry->setProtocolVersion($new->getProtocolVersion());
            foreach ($new->headers->getCookies() as $cookie) {
                $entry->headers->setCookie($cookie);
            }
        } else {
            // backend is slow as hell, send a 503 response (to avoid the dog pile effect)
            $entry->setStatusCode(503);
            $entry->setContent('503 Service Unavailable');
            $entry->headers->set('Retry-After', 10);
        }

        return true;
    }

    /**
     * Writes the Response to the cache.
     *
     * @throws \Exception
     */
    protected function store(Request $request, Response $response)
    {
        try {
            $this->store->write($request, $response);

            $this->record($request, 'store');

            $response->headers->set('Age', $response->getAge());
        } catch (\Exception $e) {
            $this->record($request, 'store-failed');

            if ($this->options['debug']) {
                throw $e;
            }
        }

        // now that the response is cached, release the lock
        $this->store->unlock($request);
    }

    /**
     * Restores the Response body.
     */
    private funct