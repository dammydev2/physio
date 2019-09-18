
    protected function _getPortableSequenceDefinition($sequence)
    {
        $sequence = array_change_key_case($sequence, CASE_LOWER);

        return new Sequence(
            $this->getQuotedIdentifierName($sequence['sequence_name']),
            (int) $sequence['increment_by'],
            (int) $sequence['min_value']
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableFunctionDefinition($function)
    {
        $function = array_change_key_case($function, CASE_LOWER);

        return $function['name'];
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableDatabaseDefinition($database)
    {
        $database = array_change_key_case($database, CASE_LOWER);

        return $database['username'];
    }

    /**
     * {@inheritdoc}
     */
    public function createDatabase($database = null)
    {
        if ($database === null) {
            $database = $this->_conn->getDatabase();
        }

        $params   = $this->_conn->getParams();
        $username = $database;
        $password = $params['password'];

        $query = 'CREATE USER ' . $username . ' IDENTIFIED BY ' . $password;
        $this->_conn->executeUpdate($query);

        $query = 'GRANT DBA TO ' . $username;
        $this->_conn->executeUpdate($query);
    }

    /**
     * @param string $table
     *
     * @return bool
     */
    public function dropAutoincrement($table)
    {
        assert($this->_platform instanceof OraclePlatform);

        $sql = $this->_platform->getDropAutoincrementSql($table);
        foreach ($sql as $query) {
            $this->_conn->executeUpdate($query);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function dropTable($name)
    {
        $this->tryMethod('dropAutoincrement', $name);

        parent::dropTable($name);
    }

    /**
     * Returns the quoted representation of the given identifier name.
     *
     * Quotes non-uppercase identifiers explicitly to preserve case
     * and thus make references to the particular identifier work.
     *
     * @param string $identifier The identifier to quote.
     *
     * @return string The quoted identifier.
     */
    private function getQuotedIdentifierName($identifier)
    {
        if (preg_match('/[a-z]/', $identifier)) {
            return $this->_platform->quoteIdentifier($identifier);
        }

        return $identifier;
    }

    /**
     * Kills sessions connected with the given user.
     *
     * This is useful to force DROP USER operations which could fail because of active user sessions.
     *
     * @param string $user The name of the user to kill sessions for.
     *
     * @return void
     */
    private function killUserSessions($user)
    {
        $sql = <<<SQL
SELECT
    s.sid,
    s.serial#
FROM
    gv\$session s,
    gv\$process p
WHERE
    s.username = ?
    AND p.addr(+) = s.paddr
SQL;

        $activeUserSessions = $this->_conn->fetc