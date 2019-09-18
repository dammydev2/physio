 SchemaException::indexDoesNotExist($indexName, $this->_name);
        }
        unset($this->_indexes[$indexName]);
    }

    /**
     * @param string[]    $columnNames
     * @param string|null $indexName
     * @param mixed[]     $options
     *
     * @return self
     */
    public function addUniqueIndex(array $columnNames, $indexName = null, array $options = [])
    {
        if ($indexName === null) {
            $indexName = $this->_generateIdentifierName(
                array_merge([$this->getName()], $columnNames),
                'uniq',
                $this->_getMaxIdentifierLength()
            );
        }

        return $this->_addIndex($this->_createIndex($columnNames, $indexName, true, false, [], $options));
    }

    /**
     * Renames an index.
     *
     * @param string      $oldIndexName The name of the index to rename from.
     * @param string|null $newIndexName The name of the index to rename to.
     *                                  If null is given, the index name will be auto-generated.
     *
     * @return self This table instance.
     *
     * @throws SchemaException If no index exists for the given current name
     *                         or if an index with the given new name already exists on this table.
     */
    public function renameIndex($oldIndexName, $newIndexName = null)
    {
        $oldIndexName           = $this->normalizeIdentifier($oldIndexName);
        $normalizedNewIndexName = $this->normalizeIdentifier($newIndexName);

        if ($oldIndexName === $normalizedNewIndexName) {
            return $this;
        }

        if (! $this->hasIndex($oldIndexName)) {
            throw SchemaException::indexDoesNotExist($oldIndexName, $this->_name);
        }

        if ($this->hasIndex($normalizedNewIndexName)) {
            throw SchemaException::indexAlreadyExists($normalizedNewIndexName, $this->_name);
        }

        $oldIndex = $this->_indexes[$oldIndexName];

        if ($oldIndex->isPrimary()) {
            $this->dropPrimaryKey();

            return $this->setPrimaryKey($oldIndex->getColumns(), $newIndexName);
        }

        unset($this->_indexes[$oldIndexName]);

        if ($oldIndex->isUnique()) {
            return $this->addUniqueIndex($oldIndex->getColumns(), $newIndexName, $oldIndex->getOptions());
        }

        return $this->addIndex($oldIndex->getColumns(), $newIndexName, $oldIndex->getFlags(), $oldIndex->getOptions());
    }

    /**
     * Checks if an index begins in the order of the given columns.
     *
     * @param string[] $columnNames
     *
     * @return bool
     */
    public function columnsAreIndexed(array $columnNames)
    {
        foreach ($this->getIndexes() as $index) {
            /** @var $index Index */
            if ($index->spansColumns($columnNames)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string[] $columnNames
     * @param string   $indexName
     * @param bool     $isUnique
     * @param bool     $isPrimary
     * @param string[] $flags
     * @param mixed[]  $options
     *
     * @return Index
     *
     * @throws SchemaException
     */
    private function _createIndex(array $columnNames, $indexName, $isUnique, $isPrimary, array $flags = [], array $options = [])
    {
        if (preg_match('(([^a-zA-Z0-9_]+))', $this->normalizeIdentifier($indexName))) {
            throw SchemaException::indexNameInvalid($indexName);
        }

        foreach ($columnNames as $columnName) {
            if (! $this->hasColumn($columnName)) {
                throw SchemaException::columnDoesNotExist($columnName, $this->_name);
            }
        }

        return new Index($indexName, $columnNames, $isUnique, $isPrimary, $flags, $options);
    }

    /**
     * @param string  $columnName
     * @param string  $typeName
     * @param mixed[] $options
     *
     * @return Column
     */
    public function addColumn($columnName, $typeName, array $options = [])
    {
        $column = new Column($columnName, Type::getType($typeName), $options);

        $this->_addColumn($column);

        return $column;
    }

    /**
     * Renames a Column.
     *
     * @deprecated
     *
     * @param string $oldColumnName
     * @param string $newColumnName
     *
     * @throws DBALException
     */
    public function renameColumn($oldColumnName, $newColumnName)
    {
        throw new DBALException('Table#renameColumn() was removed, because it drops and recreates ' .
            'the column instead. There is no fix available, because a schema diff cannot reliably detect if a ' .
            'column was renamed or one column was created and another one dropped.');
    }

    /**
     * Change Column Details.
     *
     * @param string  $columnName
     * @param mixed[] $options
     *
     * @return self
     */
    public function changeColumn($columnName, array $options)
    {
        $column = $this->getColumn($columnName);
        $column->setOptions($options);

        return $this;
    }

    /**
     * Drops a Column from the Table.
     *
     * @param string $columnName
     *
     * @return self
     */
    public function dropColumn($columnName)
    {
        $columnName = $this->normalizeIdentifier($columnName);
        unset($this->_columns[$columnName]);

        return $this;
    }

    /**
     * Adds a foreign key constraint.
     *
     * Name is inferred from the local columns.
     *
     * @param Table|string $foreignTable       Table schema instance or table name
     * @param string[]     $localColumnNames
     * @param string[]     $foreignColumnNames
     * @param mixed[]      $options
     * @param st