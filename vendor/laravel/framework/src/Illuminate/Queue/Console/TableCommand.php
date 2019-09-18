   $this->parameters();

        $this->parameters[$name] = $value;
    }

    /**
     * Unset a parameter on the route if it is set.
     *
     * @param  string  $name
     * @return void
     */
    public function forgetParameter($name)
    {
        $this->parameters();

        unset($this->parameters[$name]);
    }

    /**
     * Get the key / value list of parameters for the route.
     *
     * @return array
     *
     * @throws \LogicException
     */
    public function parameters()
    {
        if (isset($this->parameters)) {
            return $this->parameters;
        }

        throw new LogicException('Route is not bound.');
    }

    /**
     * Get the key / value list of original parameters for the route.
     *
     * @return array
     *
     * @throws \LogicException
     */
    public function originalParameters()
    {
        if (isset($this->originalParameters)) {
            return $this->originalParameters;
        }

        throw new LogicException('Route is not bound.');
    }

    /**
     * Get the key / value list of parameters without null values.
     *
     * @return array
     */
    public function parametersWithoutNulls()
    {
        return array_filter($this->parameters(), function ($p) {
            return ! is_null($p);
        });
    }

    /**
     * Get all of the parameter names for the route.
     *
     * @return array
     */
    public function parameterNames()
    {
        if (isset($this->parameterNames)) {
            return $this->parameterNames;
        }

        return $this->parameterNames = $this->compileParameterNames();
    }

    /**
     * Get the parameter names for the route.
     *
     * @return array
     */
    protected function compileParameterNames()
    {
        preg_match_all('/\{(.*?)\}/', $this->getDomain().$this->uri, $matches);

        return array_map(function ($m) {
            return trim($m, '?');
        }, $matches[1]);
    }

    /**
     * Get the parameters that are listed in the route / controller signature.
     *
     * @param  string|null  $subClass
     * @return array
     */
    public function signatureParameters($subClass = null)
    {
        return RouteSignatureParameters::fromAction($this->action, $subClass);
    }

    /**
     * Set a default value for the route.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @re