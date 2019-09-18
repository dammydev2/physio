   * @return ExpressionBuilder
     */
    public function getExpressionBuilder()
    {
        return $this->_expr;
    }

    /**
     * Establishes the connection with the database.
     *
     * @return bool TRUE if the connection was successfully established, FALSE if
     *              the connection is already open.
     */
    public function connect()
    {
        if ($this->isConnected) {
            return false;
        }

        $driverOptions = $this->params['driverOptions'] ?? [];
        $user          = $this->params['user'] ?? null;
        $password      = $this->params['password'] ?? null;

        $this->_conn       = $this->_driver->connect($this->params, $user, $password, $driverOptions);
        $this->isConnected = true;

        if ($this->autoCommit === false) {
            $this->beginTransaction();
        }

        if ($this->_eventManager->hasListeners(Events::postConnect)) {
            $eventArgs = new Event\ConnectionEventArgs($this);
            $this->_eventManager->dispatchEvent(Events::postConnect, $eventArgs);
        }

        return true;
    }

    /**
     * Detects and sets the database platform.
     *
     * Evaluates custom platform class and version in order to set the correct platform.
     *
     * @throws DBALException If an invalid platform was specified for this connection.
     */
    private function detectDatabasePlatform()
    {
        $version = $this->getDatabasePlatformVersion();

        if ($version !== null) {
            assert($this->_driver instanceof VersionAwarePlatformDriver);

            $this->platform = $this->_driver->createDatabasePlatformForVersion($version);
        } else {
            $this->platform = $this->_driver->getDatabasePlatform();
        }

        $this->platform->setEventManager($this->_eventManager);
    }

    /**
     * Returns the version of the related platform if applicable.
     *
     * Returns null if either the driver is not capable to create version
     * specific platform instances, no explicit server version was specified
     * or the underlying driver connection cannot determine the platform
     * version without having to query it (performance reasons).
     *
     * @return string|null
     *
     * @throws Exception
     */
    private function getDatabasePlatformVersion()
    {
        // Driver does not support version specific platforms.
        if (! $this->_driver instanceof VersionAwarePlatformDriver) {
            return null;
        }

        // Explicit platform version requested (supersedes auto-detection).
        if (isset(