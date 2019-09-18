 $logger = $this->_config->getSQLLogger();
        if ($logger) {
            $logger->startQuery($statement);
        }

        try {
            $result = $this->_conn->exec($statement);
        } catch (Throwable $ex) {
            throw DBALException::driverExceptionDuringQuery($this->_driver, $ex, $statement);
        }

        if ($logger) {
            $logger->stopQuery();
        }

        return $result;
    }

    /**
     * Returns the current transaction nesting level.
     *
     * @return int The nesting level. A value of 0 means there's no active transaction.
     */
    public function getTransactionNestingLevel()
    {
        return $this->transactionNestingLevel;
    }

    /**
     * Fetches the SQLSTATE associated with the last database operation.
     *
     * @return string|null The last error code.
     */
    public function errorCode()
    {
        $this->connect();

        return $this->_conn->errorCode(