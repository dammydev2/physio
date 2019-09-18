$request, $response);
        }
    }

    /**
     * Forwards the Request to the backend without storing the Response in the cache.
     *
     * @param Request $request A Request instance
     * @param bool    $catch   Whether to process exceptions
     *
     * @return Response A Response instance
     */
    protected function pass(Request $request, $catch = false)
    {
        $this->record($request, 'pass');

        return $this->forward($request, $catch);
    }

    /**
     * Invalidates non-safe methods (like POST, PUT, and DELETE).
     *
     * @param Request $request A Request instance
     * @param bool    $catch   Whether to process exceptions
     *
     * @return Response A Response instance
     *
     * @throws \Exception
     *
     * @see RFC2616 13.10
     */
    protected function invalidate(Request $request, $catch = false)
    {
        $response = $this->pass($request, $catch);

        // invalidate only when the response is successful
        if ($response->isSuccessful() || $response->isRedirect()) {
            try {
                $this->store->invalidate($request);

                // As per the RFC, invalidate Location and Content-Location URLs if present
                foreach (['Location', 'Content-Location'] as $header) {
                    if ($uri = $response->headers->get($header)) {
                        $subRequest = Request::create($uri, 'get', [], [], [], $request->server->all());

                        $this->store->invalidate($subRequest);
                    }
                }

                $this->record($request, 'invalidate');
            } catch (\Exception $e) {
                $this->record($request, 'invalidate-failed');

                if ($this->options['debug']) {
                    throw $e;
                }
            }
        }

        return $response;
    }

    /**
     * Lookups a Response from the cache for the given Request.
     *
     * When a matching cache entry is found and is fresh, it uses it as the
     * response without forwarding any request to the backend. When a matching
     * cache entry is found but is stale, it attempts to "validate" the entry with
     * the backend using conditional GET. When no matching cache entry is found,
     * it triggers "miss" processing.
     *
     * @param Request $request A Request instance
     * @param bool    $catch   Whether to process exceptions
     *
     * @return Response A Response instance
     *
     * @throws \Exception
     */
    protected function lookup(Request $request, $catch = false)
    {
        try {
            $entry = $this->store->lookup($request);
        } catch (\Exception $e) {
            $this->record($request, 'lookup-failed');

            if ($this->options['debug']) {
                throw $e;
            }

            return $this->pass($request, $catch);
        }

        if (null === $entry) {
            $this->record($request, 'miss');

            return $this->fetch($request, $catch);
        }

        if (!$this->isFreshEnough($request, $entry)) {
            $this->record($request, 'stale');

            return $this->validate($request, $entry, $catch);
        }

        $this->record($request, 'fresh');

        $entry->headers->set('Age', $entry->getAge());

        return $entry;
    }

    /**
     * Validates that a cache entry is fresh.
     *
     * The original request is used as a template for a conditional
     * GET request with the backend.
     *
     * @param Request  $request A Request instance
     * @param Response $entry   A Response instance to validate
     * @param bool     $catch   Whether to process exceptions
     *
     * @return Response A Response instance
     */
    protected function validate(Request $request, Response $entry, $catch = false)
    {
        $subRequest = clone $request;

        // send no head requests because we want content
        if ('HEAD' === $request->getMethod()) {
            $subRequest->setMethod('GET');
        }

        // add our cached last-modified validator
        $subRequest->headers->set('if_modified_since', $entry->headers->get('Last-Modified'));

        // Add our cached etag validator to the environment.
        // We keep the etags from the client to handle the case when the client
        // has a different private valid entry which is not cached here.
        $cachedEtags = $entry->getEtag() ? [$entry->getEtag()] : [];
        $requestEtags = $request->getETags();
        if ($etags = array_unique(array_merge($cachedEtags, $requestEtags))) {
            $subRequest->headers->set('if_none_match', implode(', ', $etags));
        }

        $response = $this->forward($subRequest, $catch, $entry);

        if (304 == $response->getStatusCode()) {
            $this->record($request, 'valid');

            // return the response and not the cache entry if the response is valid but not cached
            $etag = $response->getEtag();
            if ($etag && \in_array($etag, $requestEtags) && !\in_array($etag, $cachedEtags)) {
                return $response;
            }

            $entry = clone $entry;
            $entry->headers->remove('Date');

            foreach (['Date', 'Expires', 'Cache-Control', 'ETag', 'Last-Modified'] as $name) {
                if ($response->headers->has($name)) {
                    $entry->headers->set($name, $response->headers->get($name));
                }
            }

            $response = $entry;
        } else {
            $this->record($request, 'invalid');
        }

        if ($response->isCacheable()) {
            $this->store($request, $response);
        }

        return $response;
    }

    /**
     * Unconditionally fetches a fresh response from the backend and
     * stores it in the cache if is cacheable.
     *
     * @param Request $request A Request instance
     * @param bool    $catch   Whether to process exceptions
     *
     * @return Response A Response instance
     */
    protected function fetch(Request $request, $catch = false)
    {
        $subRequest = clone $request;

        // send no head requests because we want content
        if ('HEAD' === $request->getMethod()) {
            $subRequest->setMethod('GET');
        }

        // avoid