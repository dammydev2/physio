table.
     *
     * Cascade is not supported on many platforms but would optionally cascade the truncate by
     * following the foreign keys.
     *
     * @param string $tableName
     * @param bool   $cascade
     *
     * @return string
     */
    public function getTruncateTableSQL($tableName, $cascade = false)
    {
        $tableIdentifier = new Identifier($tableName);

        return 'TRUNCATE ' . $tableIdentifier->getQuotedName($this);
    }

    /**
     * This is for test reasons, many vendors have special requirements for dummy statements.
     *
     * @return string
     */
    public function getDummySelectSQL()
    {
        $expression = func_num_args() > 0 ? func_get_arg(0) : '1';

        return sprintf('SELECT %s', $expression);
    }

    /**
     * Returns the SQL to create a new savepoint.
     *
     * @param string $savepoint
     *
     * @return string
     */
    public function createSavePoint($savepoint)
    {
        return 'SAVEPOINT ' . $savepoint;
    }

    /**
     * Returns the SQL to release a savepoint.
     *
     * @param string $savepoint
     *
     * @return string
     */
    public function releaseSavePoint($savepoint)
    {
        return 'RELEASE SAVEPOINT ' . $savepoint;
    }

    /**
     * Returns the SQL to rollback a savepoint.
     *
     * @param string $savepoint
     *
     * @return string
     */
    public function rollbackSavePoint($savepoint)
    {
        return 'ROLLBACK TO SAVEPOINT ' . $savepoint;
    }

    /**
     * Returns the keyword list instance of this platform.
     *
     * @return KeywordList
     *
     * @throws DBALException If no keyword list is specified.
     */
    final public function getReservedKeywordsList()
    {
        // Check for an existing instantiation of the keywords class.
        if ($this->_keywords) {
            return $this->_keywords;
        }

        $class    = $this->getReservedKeywordsClass();
        $keywords = new $class();
        if (! $keywords instanceof KeywordList) {
            throw DBALException::notSupported(__METHOD__);
        }

        // Store the instance so it doesn't need to be generated on every request.
        $this->_keywords = $keywords;

        return $keywords;
    }

    /**
     * Returns the class name of the reserved keywords list.
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    protected function getReservedKeywordsClass()
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Quotes a literal string.
     * This method is NOT meant to fix SQL injections!
     * It is only meant to escape this platform's string literal
     * quote character inside the given literal string.
     *
     * @param string $str The literal string to be quoted.
     *
     * @return string The quoted literal string.
     */
    public function quoteStringLiteral($str)
