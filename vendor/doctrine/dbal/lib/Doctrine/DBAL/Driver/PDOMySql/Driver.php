xception
     */
    public function getIndexDeclarationSQL($name, Index $index)
    {
        $columns = $index->getColumns();
        $name    = new Identifier($name);

        if (count($columns) === 0) {
            throw new InvalidArgumentException("Incomplete definition. 'columns' required.");
        }

        return $this->getCreateIndexSQLFlags($index) . 'INDEX ' . $name->getQuotedName($this) . ' ('
            . $this->getIndexFieldDeclarationListSQL($index)
            . ')' . $this->getPartialIndexSQL($index);
    }

    /**
     * Obtains SQL code portion needed to create a custom column,
     * e.g. when a field has the "columnDefinition" keyword.
     * Only "AUTOINCREMENT" and "PRIMARY KEY" are added if appropriate.
     *
     * @param mixed[] $columnDef
     *
     * @return string
     */
    public function getCustomTypeDeclarationSQL(array $columnDef)
    {
        return $columnDef['columnDefinition'];
    }

    /**
     * Obtains DBMS specific SQL code portion needed to set an index
     * declaration to be used in statements like CREATE TABLE.
     *
     * @param mixed[]|Index $columnsOrIndex array declaration is deprecated, prefer passing Index to this method
     */
    public function getIndexFieldDeclarationListSQL($columnsOrIndex) : string
    {
        if ($columnsOrIndex instanceof Index) {
            return implode(', ', $columnsOrIndex->getQuotedColumns($this));
        }

        if (! is_array($columnsOrIndex)) {
            throw new InvalidArgumentException('Fields argument should be an Index or array.');
        }

        $ret = [];

        foreach ($columnsOrIndex as $column => $definition) {