Obtain DBMS specific SQL code portion needed to set the COLLATION
     * of a field declaration to be used in statements like CREATE TABLE.
     *
     * @deprecated Deprecated since version 2.5, Use {@link self::getColumnCollationDeclarationSQL()} instead.
     *
     * @param string $collation name of the collation
     *
     * @return string  DBMS specific SQL code portion needed to set the COLLATION
     *                 of a field declaration.
     */
    public function getCollationFieldDeclaration($collation)
    {
        return $this->getColumnCollationDeclarationSQL($collation);
    }

    /**
     * {@inheritDoc}
     *
     * MySql prefers "autoincrement" identity columns since sequences can only
     * be emulated with a table.
     */
    public function prefersIdentityColumns()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * MySql supports this through AUTO_INCREMENT columns.
     */
    public function supportsIdentityColumns()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsInlineColumnComments()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsColumnCollation()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getListTablesSQL()
    {
        return "SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'";
    }

    /**
     * {@inheritDoc}
     */
    public function getListTableColumnsSQL($table, $database = null)
    {
        $table = $this->quoteStringLiteral($table);

        if ($database) {
            $database = $this->quoteString