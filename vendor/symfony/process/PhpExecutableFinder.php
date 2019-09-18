eme = strtolower($scheme);

        return $this;
    }

    /**
     * Gets the HTTP port.
     *
     * @return int The HTTP port
     */
    public function getHttpPort()
    {
        return $this->httpPort;
    }

    /**
     * Sets the HTTP port.
     *
     * @param int $httpPort The HTTP port
     *
     * @return $this
     */
    public function setHttpPort($httpPort)
    {
        $this->httpPort = (int) $httpPort;

        return $this;
    }

    /**
     * Gets the HTTPS port.
     *
     * @return int The HTTPS port
     */
    public function getHttpsPort()
    {
        return $this->httpsPort;
    }

    /**
     * Sets the HTTPS port.
     *
     * @param int $httpsPort The HTTPS port
     *
     * @return $this
     */
    public function setHttpsPort($httpsPort)
    {
        $this->httpsPort = (int) $httpsPort;

        return $this;
    }

    /**
     * Gets the query string.
     *
     * @return string The query string without the "?"
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * Sets the query string.
     *
     * @param string $queryString The query string (after "?")
     *
     * @return $this
     */
    public function setQueryString($queryString)
    {
        // string cast to be fault-tolerant, accepting null
        $this->queryString = (string) $queryString;

        return $this;
    }

    /**
     * Returns the parameters.
     *
     * @return array The parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Sets the parameters.
     *
     * @param array $parameters The parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * Gets a parameter value.
     *
     * @param string $name A parameter name
     *
     * @return mixed The parameter value or null if nonexistent
     */
    public function getParameter($name)
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
    }

    /**
     * Checks if a parameter value is set for the given parameter.
     *
     * @param string $name A parameter name
     *
     * @return bool True if the parameter value is set, false otherwise
     */
    public function hasParameter($name)
    {
        return \array_key_exists($name, $this->parameters);
    }

    /**
     * Sets a parameter value.
     *
     * @param string $name      A parameter name
     * @param mixed  $parameter The parameter value
     *
     * @return 