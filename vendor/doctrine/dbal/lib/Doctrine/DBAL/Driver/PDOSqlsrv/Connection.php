    */
    public function getSetTransactionIsolationSQL($level)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Obtains DBMS specific SQL to be used to create datetime fields in
     * statements like CREATE TABLE.
     *
     * @param mixed[] $fieldDeclaration
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateTimeTypeDeclarationSQL(array $fieldDeclaration)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Obtains DBMS specific SQL to be used to create datetime with timezone offset fields.
     *
     * @param mixed[] $fieldDeclaration
     *
     * @return string
     */
    public function getDateTimeTzTypeDeclarationSQL(array $fieldDeclaration)
    {
        return $this->getDateTimeTypeDeclarationSQL($fieldDeclaration);
    }


    /**
     * Obtains DBMS specific SQL to be used to create date fields in statements
     * like CREATE TABLE.
     *
     * @param mixed[] $fieldDeclaration
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateTypeDeclarationSQL(array $fieldDeclaration)
    {
        throw DBALException: