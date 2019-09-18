return $serverVersion;
            }
        }

        return $this->getServerVersion();
    }

    /**
     * Returns the database server version if the underlying driver supports it.
     *
     * @return string|null
     */
    private function getServerVersion()
    {
        // Automatic platform version detection.
        if ($this->_conn instanceof ServerInfoAwareConnection &&
            ! $this->_conn->requiresQueryForServerVersion()
        ) {
            return $this->_conn->getServerVersion();
        }

        // Unable to detect platform version.
        return null;
    }

    /**
     * Returns the current auto-commit mode for this connection.
     *
     * @see    setAutoCommit
     *
     * @return bool True if auto-commit mode is currently enabled for this connection, false otherwise.
     */
    public function isAutoCommit()
    {
        return $this->autoCommit === true;
    }

    /**
     * Sets auto-commit mode for this connection.
     *
     * If a connection is in auto-commit mode, then all its SQL statements will be executed and committed as individual
     * transactions. Otherwise, its SQL statements are grouped into transactions that are terminated by a call to either
     * the method commit or the method rollback. By default, new connections are in auto-commit mode.
     *
     * NOTE: If this method is called during a transaction and the auto-commit mode is changed, the transaction is
     * committed. If this method is called and the auto-commit mode is not changed, the call is a no-op.
     *
     * @see   isAutoCommit
     *
     * @param bool $autoCommit True to enable auto-commit mode; false to disable it.
     */
    public function setAutoCommit($autoCommit)
    {
        $autoCommit = (bool) $autoCommit;

        // Mode not changed, no-op.
        if ($autoCommit === $this->autoCommit) {