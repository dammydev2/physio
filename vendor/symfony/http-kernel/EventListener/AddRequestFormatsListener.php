move('X-Body-Eval');
            if (!$response->headers->has('Transfer-Encoding')) {
                $response->headers->set('Content-Length', \strlen($response->getContent()));
            }
        } elseif ($response->headers->has('X-Body-File')) {
            // Response does not include possibly dynamic content (ESI, SSI), so we need
            // not handle the content for HEAD requests
            if (!$request->isMethod('HEAD')) {
                $response->setContent(file_get_contents($response->headers->get('X-Body-File')));
            }
        } else {
            return;
        }

        $response->headers->remove('X-Body-File');
    }

    protected function processResponseBody(Request $request, Response $response)
    {
        if (null !== $this->surrogate && $this->surrogate->needsParsing($response)) {
            $this->surrogate->process($request, $response);
        }
    }

    /**
     * Checks if the Request includes authorization or other sensitive information
     * that should cause the Response to be considered private by default.
     *
     * @return bool true if the Request is private, false otherwise
     */
    private function isPrivateRequest(Request $requ