     }

        throw new LogicException('Lost connection and no reconnector available.');
    }

    /**
     * Reconnect to the database if a PDO connection is missing.
     *
     * @return void
     */
    protected function reconnectIfMissingConnection()
    {
        if (is_null($this->pdo)) {
            $this->reconnect();
        }
    }

    /**
     * Disconnect from the underlying PDO connection.
     *
     * @return void
     */
    public function disconnect()
    {
        $this->setPdo(null)->setReadPdo(null);
    }

    /**
     * Register a database query listener with the connection.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function listen(Closure $callback)
    {
        if (isset($this->events)) {
            $this->events->listen(Events\QueryExecuted::class, $callback);
        }
    }

    /**
     * Fire an event for this connection.
     *
     * @param  string  $event
     * @return array|null
     */
    protected function fireConnectionEvent($event)
    {
        if (! isset($this->events)) {
            return;
        }

        switch ($event) {
            case 'beganTransaction':
                return $this->events->dispatch(new Events\TransactionBeginning($this));
            case 'committed':
                return $this->events->dispatch(new Events\TransactionCommitted($this));
            case 'rollingBack':
                return $this->events->dispatch(new Events\TransactionRolledBack($