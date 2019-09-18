           $query .= ' UNIQUE';
            } else {
                throw new InvalidArgumentException(
                    'Can only create primary or unique constraints, no common indexes with getCreateConstraintSQL().'
                );
            }
        } elseif ($constraint instanceof ForeignKeyConstraint) {
            $query .= ' FOREIGN KEY';

            $referencesClause = ' REFERENCES ' . $constraint->getQuotedForeignTableName($this) .
                ' (' . implode(', ', $constraint->getQuotedForeignColumns($this)) . ')';
        }
        $query .= ' ' . $columnList . $referencesClause;

        return $query;
    }

    /**
     * Returns the SQL to create an index on a table on this platform.
     *
     * @param Table|string $table The name of the table on which the index is to be created.
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function getCreateIndexSQL(Index $index, $table)
    {
        if ($table instanceof Table) {
            $table = $table->getQuotedName($this);
        }
        $name    = $index->getQuotedName($this);
        $columns = $index->getColumns();

        if (count($columns) === 0) {
            throw new InvalidArgumentException("Incomplete definition. 'columns' required.");
        }

        if ($index->isPrimary()) {
            return $this->getCreatePrimaryKeySQL($index, $table);
        }

        $query  = 'CREATE ' . $this->getCreateIndexSQLFlags($index) . 'INDEX ' . $name . ' ON ' . $table;
        $query .= ' (' . $this->getIndexFieldDeclarationListSQL($index) . ')' . $this->getPartialIndexSQL($index);

        return $query;
    }

    /**
     * Adds condition for partial index.
     *
     * @return string
     */
    protected function getPartialIndexSQL(Index $index)
    {
        if ($this->supportsPartialIndexes() && $index->hasOption('where')) {
            return ' WHERE ' . $index->getOption('where');
        }

        return '';
    }

    /**
     * Adds additional flags for index generation.
     *
     * @return string
     */
    protected function getCreateIndexSQLFlags(Index $index)
    {
        return $index->isUnique() ? 'UNIQUE ' : '';
    }

    /**
     * Returns the SQL to create an unnamed primary key constraint.
     *
     * @param Table|string $table
     *
     * @return string
     */
    public function getCreatePrimaryKeySQL(Index $index, $table)
    {
        return 'ALTER TABLE ' . $table . ' ADD PRIMARY KEY (' . $this->getIndexFieldDeclarationListSQL($index) . ')';
    }

    /**
     * Returns the SQL to create a named schema.
     *
     * @param string $schemaName
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getCreateSchemaSQL($schemaName)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Quotes a string so that it can be safely used as a table or column name,
     * even if it is a reserved word of the platform. This also detects identifier
     * chains separated by dot and quotes them independently.
     *
     * NOTE: Just because you CAN use quoted identifiers doesn't mean
     * you SHOULD use them. In general, they end up causing way more
     * problems than they solve.
     *
     * @param string $str The identifier name to be quoted.
     *
     * @return string The quoted identifier string.
     */
    public function quoteIdentifier($str)
    {
        if (strpos($str, '.') !== false) {
            $parts = array_map([$this, 'quoteSingleIdentifier'], explode('.', $str));

            return implode('.', $parts);
        }

        return $this->quoteSingleIdentifier($str);
    }

    /**
     * Quotes a single identifier (no dot chain separation).
     *
     * @param string $str The identifier name to be quoted.
     *
     * @return string The quoted identifier string.
     */
    public function quoteSingleIdentifier($str)
    {
        $c = $this->getIdentifierQuoteCharacter();

        return $c . str_replace($c, $c . $c, $str) . $c;
    }

    /**
     * Returns the SQL to create a new foreign key.
     *
     * @param ForeignKeyConstraint $foreignKey The foreign key constraint.
     * @param Table|string         $table      The name of the table on which the foreign key is to be created.
     *
     * @return string
     */
    public function getCreateForeignKeySQL(ForeignKeyConstraint $foreignKey, $table)
    {
        if ($table instanceof Table) {
            $table = $table->getQuotedName($this);
        }

        return 'ALTER TABLE ' . $table . ' ADD ' . $this->getForeignKeyDeclarationSQL($foreignKey);
    }

    /**
     * Gets the SQL statements for altering an existing table.
     *
     * This method returns an array of SQL statements, since some platforms need several statements.
     *
     * @return string[]
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getAlterTableSQL(TableDiff $diff)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * @param mixed[] $columnSql
     *
     * @return bool
     */
    protected function onSchemaAlterTableAddColumn(Column $column, TableDiff $diff, &$columnSql)
    {
        if ($this->_eventManager === null) {
            return false;
        }

        if (! $this->_eventManager->hasListeners(Events::onSchemaAlterTableAddColumn)) {
            return false;
        }

        $eventArgs = new SchemaAlterTableAddColumnEventArgs($column, $diff, $this);
        $this->_eventManager->dispatchEvent(Events::onSchemaAlterTableAddColumn, $eventArgs);

        $columnSql = array_merge($columnSql, $eventArgs->getSql());

        return $eventArgs->isDefaultPrevented();
    }

    /**
     * @param string[] $columnSql
     *
     * @return bool
     */
    protected function onSchemaAlterTableRemoveColumn(Column $column, TableDiff $diff, &$columnSql)
    {
        if ($this->_eventManager === null) {
            return false;
        }

        if (! $this->_eventManager->hasListeners(Events::onSchemaAlterTableRemoveColumn)) {
            return false;
        }

        $eventArgs = new SchemaAlterTableRemoveColumnEventArgs($column, $diff, $this);
        $this->_eventManager->dispatchEvent(Events::onSchemaAlterTableRemoveColumn, $eventArgs);

        $columnSql = array_merge($columnSql, $eventArgs->getSql());

        return $eventArgs->isDefaultPrevented();
    }

    /**
     * @param string[] $columnSql
     *
     * @return bool
     */
    protected function onSchemaAlterTableChangeColumn(ColumnDiff $columnDiff, TableDiff $diff, &$columnSql)
    {
        if ($this->_eventManager === null) {
            return false;
        }

        if (! $this->_eventManager->hasListeners(Events::onSchemaAlterTableChangeColumn)) {
            return false;
        }

        $eventArgs = new SchemaAlterTableChangeColumnEventArgs($columnDiff, $diff, $this);
        $this->_eventManager->dispatchEvent(Events::onSchemaAlterTableChangeColumn, $eventArgs);

        $columnSql = array_merge($columnSql, $eventArgs->getSql());

        return $eventArgs->isDefaultPrevented();
    }

    /**
     * @param string   $oldColumnName
     * @param string[] $columnSql
     *
     * @return bool
     */
    protected function onSchemaAlterTableRenameColumn($oldColumnName, Column $column, TableDiff $diff, &$columnSql)
    {
        if ($this->_eventManager === null) {
            return false;
        }

        if (! $this->_eventManager->hasListeners(Events::onSchemaAlterTableRenameColumn)) {
            return false;
        }

        $eventArgs = new SchemaAlterTableRenameColumnEventArgs($oldColumnName, $column, $diff, $this);
        $this->_eventManager->dispatchEvent(Events::onSchemaAlterTableRenameColumn, $eventArgs);

        $columnSql = array_merge($columnSql, $eventArgs->getSql());

        return $eventArgs->isDefaultPrevented();
    }

    /**
     * @param string[] $sql
     *
     * @return bool
     */
    protected function onSchemaAlterTable(TableDiff $diff, &$sql)
    {
        if ($this->_eventManager === null) {
            return false;
        }

        if (! $this->_eventManager->hasListeners(Events::onSchemaAlterTable)) {
            return false;
        }

        $eventArgs = new SchemaAlterTableEventArgs($diff, $this);
        $this->_eventManager->dispatchEvent(Events::onSchemaAlterTable, $eventArgs);

        $sql = array_merge($sql, $eventArgs->getSql());

        return $eventArgs->isDefaultPrevented();
    }

    /**
     * @return string[]
     */
    protected function getPreAlterTableIndexForeignKeySQL(TableDiff $diff)
    {
        $tableName = $diff->getName($this)->getQuotedName($this);

        $sql = [];
        if ($this->supportsForeignKeyConstraints()) {
            foreach ($diff->removedForeignKeys as $foreignKey) {
                $sql[] = $this->getDropForeignKeySQL($foreignKey, $tableName);
            }
            foreach ($diff->changedForeignKeys as $foreignKey) {
                $sql[] = $this->getDropForeignKeySQL($foreignKey, $tableName);
            }
        }

        foreach ($diff->removedIndexes as $index) {
            $sql[] = $this->getDropIndexSQL($index, $tableName);
        }
        foreach ($diff->changedIndexes as $index) {
            $sql[] = $this->getDropIndexSQL($index, $tableName);
        }

        return $sql;
    }

    /**
     * @return string[]
     */
    protected function getPostAlterTableIndexForeignKeySQL(TableDiff $diff)
    {
        $tableName = $diff->newName !== false
            ? $diff->getNewName()->getQuotedName($this)
            : $diff->getName($this)->getQuotedName($this);

        $sql = [];

        if ($this->supportsForeignKeyConstraints()) {
            foreach ($diff->addedForeignKeys as $foreignKey) {
                $sql[] = $this->getCreateForeignKeySQL($foreignKey, $tableName);
            }

            foreach ($diff->changedForeignKeys as $foreignKey) {
                $sql[] = $this->getCreateForeignKeySQL($foreignKey, $tableName);
            }
        }

        foreach ($diff->addedIndexes as $index) {
            $sql[] = $this->getCreateIndexSQL($index, $tableName);
        }

        foreach ($diff->changedIndexes as $index) {
            $sql[] = $this->getCreateIndexSQL($index, $tableName);
        }

        foreach ($diff->renamedIndexes as $oldIndexName => $index) {
            $oldIndexName = new Identifier($oldIndexName);
            $sql          = array_merge(
                $sql,
                $this->getRenameIndexSQL($oldIndexName->getQuotedName($this), $index, $tableName)
            );
        }

        return $sql;
    }

    /**
     * Returns the SQL for renaming an index on a table.
     *
     * @param string $oldIndexName The name of the index to rename from.
     * @param Index  $index        The definition of the index to rename to.
     * @param string $tableName    The table to rename the given index on.
     *
     * @return string[] The sequence of SQL statements for renaming the given index.
     */
    protected function getRenameIndexSQL($oldIndexName, Index $index, $tableName)
    {
        return [
            $this->getDropIndexSQL($oldIndexName, $tableName),
            $this->getCreateIndexSQL($index, $tableName),
        ];
    }

    /**
     * Common code for alter table statement generation that updates the changed Index and Foreign Key definitions.
     *
     * @return string[]
     */
    protected function _getAlterTableIndexForeignKeySQL(TableDiff $diff)
    {
        return array_merge($this->getPreAlterTableIndexForeignKeySQL($diff), $this->getPostAlterTableIndexForeignKeySQL($diff));
    }

    /**
     * Gets declaration of a number of fields in bulk.
     *
     * @param mixed[][] $fields A multidimensional associative array.
     *                          The first dimension determines the field name, while the second
     *                          dimension is keyed with the name of the properties
     *                          of the field being declared as array indexes. Currently, the types
     *                          of supported field properties are as follows:
     *
     *      length
     *          Integer value that determines the maximum length of the text
     *          field. If this argument is missing the field should be
     *          declared to have the longest length allowed by the DBMS.
     *
     *      default
     *          Text value to be used as default for this field.
     *
     *      notnull
     *          Boolean flag that indicates whether this field is constrained
     *          to not be set to null.
     *      charset
     *          Text value with the default CHARACTER SET for this field.
     *      collation
     *          Text value with the default COLLATION for this field.
     *      unique
     *          unique constraint
     *
     * @return string
     */
    public function getColumnDeclarationListSQL(array $fields)
    {
        $queryFields = [];

        foreach ($fields as $fieldName => $field) {
            $queryFields[] = $this->getColumnDeclarationSQL($fieldName, $field);
        }

        return implode(', ', $queryFields);
    }

    /**
     * Obtains DBMS specific SQL code portion needed to declare a generic type
     * field to be used in statements like CREATE TABLE.
     *
     * @param string  $name  The name the field to be declared.
     * @param mixed[] $field An associative array with the name of the properties
     *                       of the field being declared as array indexes. Currently, the types
     *                       of supported field properties are as follows:
     *
     *      length
     *          Integer value that determines the maximum length of the text
     *          field. If this argument is missing the field should be
     *          declared to have the longest length allowed by the DBMS.
     *
     *      default
     *          Text value to be used as default for this field.
     *
     *      notnull
     *          Boolean flag that indicates whether this field is constrained
     *          to not be set to null.
     *      charset
     *          Text value with the default CHARACTER SET for this field.
     *      collation
     *          Text value with the default COLLATION for this field.
     *      unique
     *          unique constraint
     *      