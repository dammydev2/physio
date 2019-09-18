 is returned if no rows are found.
     *
     * @throws DBALException
     */
    public function fetchColumn($statement, array $params = [], $column = 0, array $types = [])
    {
        return $this->executeQuery($statement, $params, $types)->fetchColumn($column);
    }

    /**
     * Whether an actual connection to the database is established.
     *
     * @return bool
     */
    public function isConnected()
    {
        return $this->isConnected;
    }

    /**
     * Checks whether a transaction is currently active.
     *
     * @return bool TRUE if a transaction is currently active, FALSE otherwise.
     */
    public function isTransactionActive()
    {
        return $this->transactionNestingLevel > 0;
    }

    /**
     * Gathers conditions for an update or delete call.
     *
     * @param mixed[] $identifiers Input array of columns to values
     *
     * @return string[][] a triplet with:
     *                    - the first key being the columns
     *                    - the second key being the values
     *                    - the third key being the conditions
     */
    private function gatherConditions(array $identifiers)
    {
        $columns    = [];
        $values     = [];
        $conditions = [];

        foreach ($