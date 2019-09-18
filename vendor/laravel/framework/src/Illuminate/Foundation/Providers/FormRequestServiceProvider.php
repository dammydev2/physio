        return $route->parameter($param, $default);
    }

    /**
     * Get a unique fingerprint for the request / route / IP address.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function fingerprint()
    {
        if (! $route = $this->route()) {
            throw new RuntimeException('Unable to generate fingerprint. Route unavailable.');
        }

        return sha1(implode('|', array_merge(
            $route->methods(),
            [$route->getDomain(), $route->uri(), $this->ip()]
        )));
    }

    /**
     * Set the JSON payload for the request.
     *
     * @param  \Symfony\Component\HttpFoundation\ParameterBag  $json
     * @return $this
     */
    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * Get the user resolver callback.
     *
     * @return \Closure
     */
    public function getUserResolver()
    {
   