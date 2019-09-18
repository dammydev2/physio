abase.fetch'] = $fetchMode;

        return $this;
    }

    /**
     * Get the database manager instance.
     *
     * @return \Illuminate\Database\DatabaseManager
     */
    public function getDatabaseManager()
    {
        return $this->manager;
    }

    /**
     * Get the current event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher|null
     */
    public function getEventDispatcher()
    {
        if ($this->container->bound('events')) {
            return $this->container['events'];
        }
    }

    /**
     * Set the event dispatcher instance to be used by connections.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     * @return void
     */
    public function setEventDispatcher(Dispatcher $dispatcher)
    {
        $this->container->instance('events', $dispatcher);
    }

    /**
     * Dynamically pass methods to the default connection.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public static function __