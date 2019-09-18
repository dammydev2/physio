exes with column length definitions.
     */
    public function supportsColumnLengthIndexes() : bool
    {
        return false;
    }

    /**
     * Whether the platform supports altering tables.
     *
     * @return bool
     */
    public function supportsAlterTable()
    {
        return true;
    }

    /**
     * Whether the platform supports transactions.
     *
     * @return bool
     */
    public function supportsTransactions()
    {
        return true;
    }

    /**
     * Whether the platform supports savepoints.
     *
     * @return bool
     */
    public function supportsSavepoints()
    {
        return true;
    }

    /**
     * Whether the platform supports releasing savepoints.
     *
     * @return bool
     */
    public function supportsReleaseSavepoints()
    {
        return $this->supportsSavepoints();
    }

    /**
     * Whether the platform supports primary key constraints.
     *
     * @return bool
     */
    public function supportsPrimaryConstraints()
    {
        return true;
    }

    /**
     * Whether the platform supports foreign key constraints.
     *
     * @return bool
     */
    public function supportsForeignKeyConstraints()
    {
        return true;
    }

    /**
     * Whether this platform supports onUpdate in foreign key constraints.
     *
     * @return bool
     */
    public function supportsForeignKeyOnUpdate()
    {
        return $this->supportsForeignKeyConstraints() && true;
    }

    /**
     * Whether the platform supports database schemas.
     *
     * @return bool
     */
    public function supportsSchemas()
    {
        return false;
    }

    /**
     * Whether this platform can emulate schemas.
     *
     * Platforms that either support or emulate schemas don't automatically
     * filter a schema for the namespaced elements in {@link
     * AbstractManager#createSchema}.
     *
     * @return bool
     */
    public function canEmulateSchemas()
    {
        return false;
    }

    /**
     * Returns the default schema name.
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDefaultSchemaName()
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Whether this platform supports create database.
     *
     * Some databases don't allow to create and drop databases at all or only with certain tools.
     *
     * @return bool
     */
    public function supportsCreateDropDatabase()
    {
        return true;
    }

    /**
     * Whether the platform supports getting the affected rows of a recent update/delete type query.
     *
     * @return bool
     */
    public fu