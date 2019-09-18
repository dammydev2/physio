
     * @param string $table
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getListTableConstraintsSQL($table)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * @param string      $table
     * @param string|null $database
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getListTableColumnsSQL($table, $database = null)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getListTablesSQL()
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getListUsersSQL()
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Returns the SQL to list all views of a database or user.
     *
     * @param string $database
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getListViewsSQL($database)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Returns the list of indexes for the current database.
     *
     * The current database parameter is optional but will always be passed
     * when using the SchemaManager API and is the database the given table is in.
     *
     * Attention: Some platforms only support currentDatabase when they
     * are connected with that database. Cross-database information schema
     * requests may be impossible.
     *
     * @param string $table
     * @param string $currentDatabase
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getListTableIndexesSQL($table, $currentDatabase = null)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * @param string $table
     *
   