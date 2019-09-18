    */
    public function beginTransaction()
    {
        $this->connect();

        ++$this->transactionNestingLevel;

        $logger = $this->_config->getSQLLogger();

        if ($this->transactionNestingLevel === 1) {
            if ($logger) {
                $logger->startQuery('"START TRANSACTION"');
            }
            $this->_conn->beginTransaction();
            if ($logger) {
                $logger->stopQuery();
            }
        } elseif ($this->nestTransactionsWithSavepoints) {
            if ($logger) {
                $logger->startQuery('"SAVEPOINT"');
            }
            $this->createSavepoint($this->_getNestedTransactionSavePointName());
            if ($logger) {
               