COLUMN_NAME, DATA_TYPE, COLUMN_COMMENT, IS_NULLABLE, IS_AUTO_INCREMENT, CHARACTER_MAXIMUM_LENGTH, COLUMN_DEFAULT,' .
               ' NUMERIC_PRECISION, NUMERIC_SCALE, COLLATION_NAME' .
               ' FROM DATA_DICTIONARY.COLUMNS' .
               ' WHERE TABLE_SCHEMA=' . $databaseSQL . ' AND TABLE_NAME = ' . $this->quoteStringLiteral($table);
    }

    /**
     * {@inheritDoc}
     */
    public function getListTableForeignKeysSQL($table, $database = null)
    {
        if ($database) {
            $databaseSQL = $this->quoteStringLiteral($database);
        } else {
            $databaseSQL = 'DATABASE()';
        }

        return 'SELECT CONSTRAINT_NAME, CONSTRAINT_COLUMNS, REFERENCED_TABLE_NAME, REFERENCED_TABLE_COLUMNS, UPDATE_RULE, DELETE_RULE' .
               ' FROM DATA_DICTIONARY.FOREIGN_KEYS' .
               ' WHERE CONSTRAINT_SCHEMA=' . $databaseSQL . ' AND CONSTRAINT_TABLE=' . $this->quoteStringLiteral($table);
    }

    /**
     * {@inheritDoc}
     */
    public function getListTableIndexesSQL($table, $database = null)
    {
        if ($database) {
            $databaseSQL = $this->quoteStringLiteral($database);
        } else {
            $databaseSQL = 'DATABASE()';
        }

        return "SELECT INDEX_NAME AS 'key_name', COLUMN_NAME AS 'column_name', IS_USED_IN_PRIMARY AS 'primary', IS_UNIQUE=0 AS 'non_unique'" .
               ' FROM DATA_DICTIONARY.INDEX_PARTS' .
               ' WHERE TABLE_SCHEMA=' . $databaseSQL . ' AND TABLE_NAME=' . $this->quoteStringLiteral($table);
    }

    /**
     * {@inheritDoc}
     */
    public function prefersIdentityColumns()
    {
        return true;
    }

    /**
     * {@inheritDoc}
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
    public function supportsViews()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsColumnCollation()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getDropIndexSQL($index, $table = null)
    {
        if ($index instanceof Index) {
            $indexName = $index->getQuotedName($this);
        } elseif (is_string($index)) {
            $indexName = $index;
        } else {
            throw new InvalidArgumentException('DrizzlePlatform::getDropIndexSQL() expects $index parameter to be string or \Doctrine\DBAL\Schema\Index.');
        }

        if ($table instanceof Table) {
            $table = $table->getQuotedName($this);
        } elseif (! is_string($table)) {
            throw new InvalidArgumentException('DrizzlePlatform::getDropIndexSQL() expects $table parameter to be string or \Doctrine\DBAL\Schema\Table.');
        }

        if ($index instanceof Index && $index->isPrimary()) {
            // drizzle primary keys are always named "PRIMARY",
            // so we cannot use them in statements because of them being keyword.
            return $this->getDropPrimaryKeySQL($table);
        }

        return 'DROP INDEX ' . $indexName . ' ON ' . $table;
    }

    /**
     * {@inheritDoc}
     */
    protected function getDropPrimaryKeySQL($table)
    {
        return 'ALTER TABLE ' . $table . ' DROP PRIMARY KEY';
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTimeTypeDeclarationSQL(array $fieldDeclaration)
    {
        if (isset($fieldDeclaration['version']) && $fieldDeclaration['version'] === true) {
            return 'TIMESTAMP';
        }

        return 'DATETIME';
    }

    /**
     * {@inheritDoc}
     */
    public function getTimeTypeDeclarationSQL(array $fieldDeclaration)
    {
        return 'TIME';
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTypeDeclarationSQL(array $fieldDeclaration)
    {
        return 'DATE';
    }

    /**
     * {@inheritDoc}
     */
    public function getAlterTableSQL(TableDiff $diff)
    {
        $columnSql  = [];
        $queryParts = [];

        if ($diff->newName !== false) {
            $queryParts[] =  'RENAME TO ' . $diff->getNewName()->getQuotedName($this);
        }

        foreach ($diff->addedColumns as $column) {
            if ($this->onSchemaAlterTableAddColumn($column, $diff, $columnSql)) {
                continue;
            }

            $columnArray            = $column->toArray();
            $columnArray['comment'] = $this->getColumnComment($column);
            $queryParts[]           = 'ADD ' . $this->getColumnDeclarationSQL($column->getQuotedName($this), $columnArray);
        }

        foreach ($diff->removedColumns as $column) {
            if ($this->onSchemaAlterTableRemoveColumn($column, $diff, $columnSql)) {
                continue;
            }

            $queryParts[] =  'DROP ' . $column->getQuotedName($this);
        }

        foreach ($diff->changedColumns as $columnDiff) {
            if ($this->onSchemaAlterTableChangeColumn($columnDiff, $diff, $columnSql)) {
                continue;
            }

            $column      = $columnDiff->column;
            $columnArray = $column->toArray();

            // Do not generate column alteration clause if type is binary and only fixed property has changed.
            // Drizzle only supports binary type columns with variable length.
            // Avoids unnecessary table alteration statements.
            if ($columnArray['type'] instanceof BinaryType &&
                $columnDiff->hasChanged('fixed') &&
                count($columnDiff->changedProperties) === 1
            ) {
                continue;
            }

            $columnArray['comment'] = $this->getColumnComment($column);
            $queryParts[]           =  'CHANGE ' . ($columnDiff->getOldColumnName()->getQuotedName($this)) . ' '
                    . $this->getColumnDeclarationSQL($column->getQuotedName($this), $columnArray);
        }

        foreach ($diff->renamedColumns as $oldColumnName => $column) {
            if ($this->onSchemaAlterTableRenameColumn($oldColumnName, $column, $diff, $columnSql)) {
                continue;
            }

            $oldColumnName = new Identifier($oldColumnName);

            $columnArray            = $column->toArray();
            $columnArray['comment'] = $this->getColumnComment($column);
            $queryParts[]           =  'CHANGE ' . $oldColumnName->getQuotedName($this) . ' '
                    . $this->getColumnDeclarationSQL($column->getQuotedName($this), $columnArray);
        }

        $sql      = [];
        $tableSql = [];

        if (! $this->onSchemaAlterTable($diff, $tableSql)) {
            if (count($queryParts) > 0) {
                $sql[] = 'ALTER TABLE ' . $diff->getName($this)->getQuotedName($this) . ' ' . implode(', ', $queryParts);
            }
            $sql = array_merge(
                $this->getPreAlterTableIndexForeignKeySQL($diff),
                $sql,
                $this->getPostAlterTableIndexForeignKeySQL($diff)
            );
        }

        return array_merge($sql, $tableSql, $columnSql);
    }

    /**
     * {@inheritDoc}
     */
    public function getDropTemporaryTableSQL($table)
    {
        if ($table instanceof Table) {
            $table = $table->getQuotedName($this);
        } elseif (! is_string($table)) {
            throw new InvalidArgumentException('getDropTableSQL() expects $table parameter to be string or \Doctrine\DBAL\Schema\Table.');
        }

        return 'DROP TEMPORARY TABLE ' . $table;
    }

    /**
     * {@inheritDoc}
     */
    public function convertBooleans($item)
    {
        if (is_array($item)) {
            foreach ($item as $key => $value) {
                if (! is_bool($value) && ! is_numeric($item)) {
                    continue;
                }

                $item[$key] = $value ? 'true' : 'false';
            }
        } elseif (is_bool($item) || is_numeric($item)) {
            $item = $item ? 'true' : 'false';
        }

        return $item;
    }

    /**
     * {@inheritDoc}
     */
    public function getLocateExpression($str, $substr, $startPos = false)
    {
        if ($startPos === false) {
            return 'LOCATE(' . $substr . ', ' . $str . ')';
        }

        return 'LOCATE(' . $substr . ', ' . $str . ', ' . $startPos . ')';
    }

    /**
     * {@inheritDoc}
     *
     * @deprecated Use application-generated UUIDs instead
     */
    public function getGuidExpression()
    {
        return 'UUID()';
    }

    /**
     * {@inheritDoc}
     */
    public function getRegexpExpression()
    {
        return 'RLIKE';
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                