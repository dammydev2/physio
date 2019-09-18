t($this->requestStack->getParentRequest());
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $this->setCurrentRequest($request);

        if ($request->attributes->has('_controller')) {
            // routing is already done
            return;
        }

        // add attributes based on the request (routing)
        try {
            // matching a request is more powerful than matching a URL path + context, so try that first
            if ($this->matcher instanceof RequestMatcherInterface) {
                $parameters = $this->matcher->matchRequest($request);
            } else {
                $parameters = $this->matcher->match($request->getPathInfo());
            }

            if (null !== $this->logger) {
                $this->logger->info('Matched route "{route}".', [
                    'route' => isset($parameters['_route']) ? $parameters['_route'] : 'n/a',
                    'route_parameters' => $parameters,
                    'request_uri' => $request->getUri(),
                    'method' => $request->getMethod(),
                ]);
            }

            $request->attributes->add($parameters);
            unset($parameters['_route'], $parameters['_controller']);
            $request->attributes->set('_route_params', $parameters);
        } catch (ResourceNotFoundException $e) {
            $message = sprintf('No route found for "%s %s"', $request->getMethod(), $request->getPathInfo());

            if ($referer = $request->headers->get('referer')) {
                $message .= sprintf(' (from "%s")', $referer);
            }

            throw new NotFoundHttpException($message, $e);
        } catch (MethodNotAllowedException $e) {
            $message = sprintf('No route found for "%s %s": Method Not Allowed (Allow: %s)', $request->getMethod(), $request->getPathInfo(), implode(', ', $e->getAllowedMethods()));

            throw new MethodNotAllowedHttpException($e->getAllowedMethods(), $message, $e);
        }
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$this->debug || !($e = $event->getException()) instanceof NotFoundHttpException) {
            return;
        }

        if ($e->getPrevious