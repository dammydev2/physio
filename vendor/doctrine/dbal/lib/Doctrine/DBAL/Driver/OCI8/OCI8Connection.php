the specified name, columns and constraints
     * on this platform.
     *
     * @param int $createFlags
     *
     * @return string[] The sequence of SQL statements.
     *
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function getCreateTableSQL(Table $table, $createFlags = self::CREATE_INDEXES)
    {
        if (! is_int($createFlags)) {
            throw new InvalidArgumentException('Second argument of AbstractPlatform::getCreateTableSQL() has to be integer.');
        }

        if (count($table->getColumns()) === 0) {
            throw DBALException::noColumnsSpecifiedForTable($table->getName());
        }

        $tableName                    = $table->getQuotedName($this);
        $options                      = $table->getOptions();
        $options['uniqueConstraints'] = [];
        $options['indexes']           = [];
        $options['primary']           = [];

        if (($createFlags&self::CREATE_INDEXES) > 0) {
            foreach ($table->getIndexes() as $index) {
                /** @var $index Index */
                if ($index->isPrimary()) {
                    $options['primary']       = $index->getQuotedColumns($this);
                    $options['primary_index'] = $index;
                } else {
                    $options['indexes'][$index->getQuotedName($this)] = $index;
                }
            }
        }

        $columnSql = [];
        $columns   = [];

        foreach ($table->getColumns() as $column) {
            if ($this->_eventManager !== null && $this->_eventManager->hasListeners(Events::onSchemaCreateTableColumn)) {
                $eventArgs = new SchemaCreateTableColumnEventArgs($column, $table, $this);
                $this->_eventManager->dispatchEvent(Events::onSchemaCreateTableColumn, $eventArgs);

                $columnSql = array_merge($columnSql, $eventArgs->getSql());

                if ($eventArgs->isDefaultPrevented()) {
                    continue;
                }
            }

            $columnData            = $column->toArray();
            $columnData['name']    = $column->getQuotedName($this);
            $columnData['version'] = $column->hasPlatformOption('version') ? $column->getPlatformOption('version') : false;
            $columnData['comment'] = $this->getColumnComment($column);

            if ($columnData['type'] instanceof Types\StringType && $columnData['length'] === null) {
                $columnData['length'] = 255;
            }

            if (in_array($column->getName(), $options['primary'])) {
                $columnData['primary'] = true;
            }

            $columns[$columnData['name']] = $columnData;
        }

        if (($createFlags&self::CREATE_FOREIGNKEYS) > 0) {
            $options['foreignKeys'] = [];
            foreach ($table->getForeignKeys() as $fkConstraint) {
                $options['foreignKeys'][] = $fkConstraint;
            }
        }

        if ($this->_eventManager !== null && $this->_eventManager->hasListeners(Events::onSchemaCreateTable)) {
            $eventArgs = new SchemaCreateTableEventArgs($table, $columns, $options, $this);
            $this->_eventManager->dispatchEvent(Events::onSchemaCreateTable, $eventArgs);

            if ($eventArgs->isDefaultPrevented()) {
                return array_merge($eventArgs->getSql(), $columnSql);
            }
        }

        $sql = $this->_getCreateTableSQL($tableName, $columns, $options);
        if ($this->supportsCommentOnStatement()) {
            foreach ($table->getColumns() as $column) {
                $comment = $this->getColumnComment($column);

                if ($comment === null || $comment === '') {
                    continue;
                }

                $sql[] = $this->getCommentOnColumnSQL($tableName, $column->getQuotedName($this), $comment);
            }
        }

        return array_merge($sql, $columnSql);
    }

    /**
     * @param string $tableName
     * @param string $columnName
     * @param string $comment
     *
     * @return string
     */
    public function getCommentOnColumnSQL($tableName, $columnName, $comment)
    {
        $tableName  = new Identifier($tableName);
        $columnName = new Identifier($columnName);
        $comment    = $this->quoteStringLiteral($comment);

        return sprintf(
            'COMMENT ON COLUMN %s.%s IS %s',
            $tableName->getQuotedName($this),
            $columnName->getQuotedName($this),
            $comment
        );
    }

    /**
     * Returns the SQL to create inline comment on a column.
     *
     * @param string $comment
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getInlineColumnCommentSQL($comment)
    {
        if (! $this->supportsInlineColumnComments()) {
            throw DBALException::notSupported(__METHOD__);
        }

        return 'COMMENT ' . $this->quoteStringLiteral($comment);
    }

    /**
     * Returns the SQL used to create a table.
     *
     * @param string    $tableName
     * @param mixed[][] $columns
     * @param mixed[]   $options
     *
     * @return string[]
     */
    protected function _getCreateTableSQL($tableName, array $columns, array $options = [])
    {
        $columnListSql = $this->getColumn