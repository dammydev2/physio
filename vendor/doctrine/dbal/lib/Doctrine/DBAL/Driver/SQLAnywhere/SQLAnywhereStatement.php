lic function getCurrentTimestampSQL()
    {
        return 'CURRENT TIMESTAMP';
    }

    /**
     * {@inheritDoc}
     */
    public function getIndexDeclarationSQL($name, Index $index)
    {
        // Index declaration in statements like CREATE TABLE is not supported.
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    protected function _getCreateTableSQL($tableName, array $columns, array $options = [])
    {
        $indexes = [];
        if (isset($options['indexes'])) {
            $indexes = $options['indexes'];
        }
        $options['indexes'] = [];

        $sqls = parent::_getCreateTableSQL($tableName, $columns, $options);

        foreach ($indexes as $definition) {
            $sqls[] = $this->getCreateIndexSQL($definition, $tableName);
        }
        return $sqls;
    }

    /**
     * {@inheritDoc}
     */
    public function getAlterTableSQL(TableDiff $diff)
    {
        $sql         = [];
        $columnSql   = [];
        $commentsSQL = [];

        $queryParts = [];
        foreach ($diff->addedColumns as $column) {
            if ($this->onSchemaAlterTableAddColumn($column, $diff, $columnSql)) {
                continue;
            }

            $columnDef = $column->toArray();
            $queryPart = 'ADD COLUMN ' . $this->getColumnDeclarationSQL($column->getQuotedName($this), $columnDef);

            // Adding non-nullable columns to a table requires a default value to be specified.
            if (! empty($columnDef['notnull']) &&
                ! isset($columnDef['default']) &&
                empty($columnDef['autoincrement'])
            ) {
                $queryPart .= ' WITH DEFAULT';
            }

            $queryParts[] = $queryPart;

            $comment = $this->getColumnComment($column);

            if ($comment === null || $comment === '') {
                continue;
            }

            $commentsSQL[] = $this->getCommentOnColumnSQL(
                $diff->getName($this)->getQuotedName($this),
                $column->getQuotedName($this),
                $comment
            );
        }

        foreach ($diff->removedColumns as $column) {
            if ($this->onSchemaAlterTableRemoveColumn($column, $diff, $columnSql)) {
                continue;
            }

            $queryParts[] =  'DROP COLUMN ' . $column->getQuotedName($this);
        }

        foreach ($diff->changedColumns as $columnDiff) {
            if ($this->onSchemaAlterTableChangeColumn($columnDiff, $diff, $columnSql)) {
                continue;
            }

            if ($columnDiff->hasChanged('comment')) {
                $commentsSQL[] = $this->getCommentOnColumnSQL(
                    $diff->getName($this)->getQuotedName($this),
                    $columnDiff->column->getQuotedName($this),
                    $this->getColumnComment($columnDiff->column)
                );

                if (count($columnDiff->changedProperties) === 1) {
                    continue;
                }
            }

            $this->gatherAlterColumnSQL($diff->fromTable, $columnDiff, $sql, $queryParts);
        }

        foreach ($diff->renamedColumns as $oldColumnName => $column) {
            if ($this->onSchemaAlterTableRenameColumn($oldColumnName, $column, $diff, $columnSql)) {
                continue;
            }

            $oldColumnName = new Identifier($oldColumnName);

            $queryParts[] =  'RENAME COLUMN ' . $oldColumnName->getQuotedName($this) .
                ' TO ' . $column->getQuotedName($this);
        }

        $tableSql = [];

        if (! $this->onSchemaAlterTable($diff, $tableSql)) {
            if (count($queryParts) > 0) {
                $sql[] = 'ALTER TABLE ' . $diff->getName($this)->getQuotedName($this) . ' ' . implode(' ', $queryParts);
            }

            // Some table alteration operations require a table reorganization.
            if (! empty($diff->removedColumns) || ! empty($diff->changedColumns)) {
                $sql[] = "CALL SYSPROC.ADMIN_CMD ('REORG TABLE " . $diff->getName($this)->getQuotedName($this) . "')";
            }

            $sql = array_merge($sql, $commentsSQL);

            if ($diff->newName !== false) {
                $sql[] =  'RENAME TABLE ' . $diff->getName($this)->getQuotedName($this) . ' TO ' . $diff->getNewName()->getQuotedName($this);
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
     * Gathers the table alteration SQL for a given column diff.
     *
     * @param Table      $table      The table to gather the SQL for.
     * @param ColumnDiff $columnDiff The column diff to evaluate.
     * @param string[]   $sql        The sequence of table alteration statements to fill.
     * @param mixed[]    $queryParts The sequence of column alteration clauses to fill.
     */
    private function gatherAlterColumnSQL(Table $table, ColumnDiff $columnDiff, array &$sql, array &$queryParts)
    {
        $alterColumnClauses = $this->getAlterColumnClausesSQL($columnDiff);

        if (empty($alterColumnClauses)) {
            return;
        }

        // If we have a single column alteration, we can append the clause to the main query.
        if (count($alterColumnClauses) === 1) {
            $queryParts[] = current($alterColumnClauses);

            return;
        }

        // We have multiple alterations for the same column,
        // so we need to trigger a complete ALTER TABLE statement
        // for each ALTER COLUMN clause.
        foreach ($alterColumnClauses as $alterColumnClause) {
            $sql[] = 'ALTER TABLE ' . $table->getQuotedName($this) . ' ' . $alterColumnClause;
        }
    }

    /**
     * Returns the ALTER COLUMN SQL clauses for altering a column described by the given column diff.
     *
     * @param ColumnDiff $columnDiff The column diff to evaluate.
     *
     * @return string[]
     */
    private function getAlterColumnClausesSQL(ColumnDiff $columnDiff)
    {
        $column = $columnDiff->column->toArray();

        $alterClause = 'ALTER COLUMN ' . $columnDiff->column->getQuotedName($this);

        if ($column['columnDefinition']) {
            return [$alterClause . ' ' . $column['columnDefinition']];
        }

        $clauses = [];

        if ($columnDiff->hasChanged('type') ||
            $columnDiff->hasChanged('length') ||
            $columnDiff->hasChanged('precision') ||
            $columnDiff->hasChanged('scale') ||
            $columnDiff->hasChanged('fixed')
        ) {
            $clauses[] = $alterClause . ' SET DATA TYPE ' . $column['type']->getSQLDeclaration($column, $this);
        }

        if ($columnDiff->hasChanged('notnull')) {
            $clauses[] = $column['notnull'] ? $alterClause . ' SET NOT NULL' : $alterClause . ' DROP NOT NULL';
        }

        if ($columnDiff->hasChanged('default')) {
            if (isset($column['default'])) {
                $defaultClause = $this->getDefaultValueDeclarationSQL($column);

                if ($defaultClause) {
                    $clauses[] = $alterClause . ' SET' . $defaultClause;
                }
            } else {
                $clauses[] = $alterClause . ' DROP DEFAULT';
            }
        }

        return $clauses;
    }

    /**
     * {@inheritDoc}
     */
    protected function getPreAlterTableIndexForeignKeySQL(TableDiff $diff)
    {
        $sql   = [];
        $table = $diff->getName($this)->getQuotedName($this);

        foreach ($diff->removedIndexes as $remKey => $remIndex) {
            foreach ($diff->addedIndexes as $addKey => $addIndex) {
                if ($remIndex->getColumns() === $addIndex->getColumns()) {
                    if ($remIndex->isPrimary()) {
                        $sql[] = 'ALTER TABLE ' . $table . ' DROP PRIMARY KEY';
                    } elseif ($remIndex->isUnique()) {
                        $sql[] = 'ALTER TABLE ' . $table . ' DROP UNIQUE ' . $remIndex->getQuotedName($this);
                    } else {
                        $sql[] = $this->getDropIndexSQL($remIndex, $table);
                    }

                    $sql[] = $this->getCreateIndexSQL($addIndex, $table);

                    unset($diff->removedIndexes[$remKey], $diff->addedIndexes[$addKey]);

                    break;
                }
            }
        }

        $sql = array_merge($sql, parent::getPreAlterTableIndexForeignKeySQL($diff));

        return $sql;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRenameIndexSQL($oldIndexName, Index $index, $tableName)
    {
        if (strpos($tableName, '.') !== false) {
            [$schema]     = explode('.', $tableName);
            $oldIndexName = $schema . '.' . $oldIndexName;
        }

        return ['RENAME INDEX ' . $oldIndexName . ' TO ' . $index->getQuotedName($this)];
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultValueDeclarationSQL($field)
    {
        if (! empty($field['autoincrement'])) {
            return '';
        }

        if (isset($field['version']) && $field['version']) {
            if ((string) $field['type'] !== 'DateTime') {
                $field['default'] = '1';
            }
        }

        return parent::getDefaultValueDeclarationSQL($field);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmptyIdentityInsertSQL($tableName, $identifierColumnName)
    {
        return 'INSERT INTO ' . $tableName . ' (' . $identifierColumnName . ') VALUES (DEFAULT)';
    }

    /**
     * {@inheritDoc}
     */
    public function getCreateTemporaryTableSnippetSQL()
    {
        return 'DECLARE GLOBAL TEMPORARY TABLE';
    }

    /**
     * {@inheritDoc}
     */
    public function getTemporaryTableName($tableName)
    {
        return 'SESSION.' . $tableName;
    }

    /**
     * {@inheritDoc}
     */
    protected function doModifyLimitQuery($query, $limit, $offse