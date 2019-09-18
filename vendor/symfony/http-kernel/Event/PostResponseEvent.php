tale_if_error' => 60,
        ], $options);
    }

    /**
     * Gets the current store.
     *
     * @return StoreInterface A StoreInterface instance
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Returns an array of events that took place during processing of the last request.
     *
     * @return array An array of events
     */
    public function getTraces()
    {
        return $this->traces;
    }

    /**
     * Returns a log message for the events of the last request processing.
     *
     * @return string A log message
     */
    public function getLog()
    {
        $log = [];
        foreach ($this->traces as $request => $traces) {
            $log[] = sprintf('%s: %s', $request, implode(', ', $traces));
        }

        return implode('; ', $log);
    }

    /**
     * Gets the Request instance associated with the master request.
     *
     * @return Request A Request instance
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Gets the Kernel instance.
     *
     * @return HttpKernelInterface An HttpKernelInterface instance
     */
    public function getKe