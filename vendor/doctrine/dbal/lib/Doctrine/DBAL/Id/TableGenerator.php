tedName($this);
        $table      = $table->getQuotedName($this);

        return 'ALTER TABLE ' . $table . ' DROP CONSTRAINT ' . $foreignKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdvancedForeignKeyOptionsSQL(ForeignKeyConstraint $foreignKey)
    {
        $referentialAction = null;

        if ($foreignKey->hasOption('onDelete')) {
            $referentialAction = $this->getForeignKeyReferentialActionSQL($foreignKey->getOption('onDelete'));
        }

        return $referentialAction ? ' ON DELETE ' . $referentialAction : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getForeignKeyReferentialActionSQL($action)
    {
        $action = strtoupper($action);

        switch ($action) {
            case 'RESTRICT': // RESTRICT is not supported, therefore falling back to NO ACTION.
            case 'NO ACTION':
                // NO ACTION cannot be declared explicitly,
                // therefore returning empty string to indicate to OMIT the referential clause.
                return '';

            case 'CASCADE':
            case 'SET NULL':
                return $action;

            default:
                // SET DEFAULT is not supported, throw exception instead.
                throw new InvalidArgumentException('Invalid foreign key action: ' . $action);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getDropDatabaseSQL($database)
    {
        return 'DROP USER ' . $database . ' CASCADE';
    }

    /**
     * {@inheritDoc}
     */
    public function getAlterTableSQL(TableDiff $diff)
    {
        $sql         = [];
        $commentsSQL = [];
        $columnSql   = [];

        $fields = [];

        foreach ($diff->addedColumns as $column) {
            if ($this->onSchemaAlterTableAddColumn($column, $diff, $columnSql)) {
                continue;
            }

            $fields[] = $this->getColumnDeclarationSQL($column->getQuotedName($this), $column->toArray());
            $comment  = $this->getColumnComment($column);

            if (! $comment) {
                continue;
            }

            $commentsSQL[] = $this->getCommentOnColumnSQL(
                $diff->getName($this)->getQuotedName($this),
                $column->getQuotedName($this),
                $comment
            );
        }

        if (count($fields)) {
            $sql[] = 'ALTER TABLE ' . $diff->getName($this)->getQuotedName($this) . ' ADD (' . implode(', ', $fields) . ')';
        }

        $fields = [];
        foreach ($diff->changedColumns as $columnDiff) {
            if ($this->onSchemaAlterTableChangeColumn($columnDiff, $diff, $columnSql)) {
                continue;
            }

            $column = $columnDiff->column;

            // Do not generate column alteration clause if type is binary and only fixed property has changed.
            // Oracle only supports binary type columns with variable length.
            // Avoids unnecessary table alteration statements.
            if ($column->getType() instanceof BinaryType &&
                $columnDiff->hasChanged('fixed') &&
                count($columnDiff->changedProperties) === 1
            ) {
                continue;
            }

            $columnHasChangedComment = $columnDiff->hasChanged('comment');

            /**
             * Do not add query part if only comment has changed
             */
            if (! ($columnHasChangedComment && count($columnDiff->changedProperties) === 1)) {
                $columnInfo = $column->toArray();

                if (! $columnDiff->hasChanged('notnull')) {
                    unset($columnInfo['notnull']);
                }

                $fields[] = $column->getQuotedName($this) . $this->getColumnDeclarationSQL('', $columnInfo);
            }

            if (! $columnHasChangedComment) {
                continue;
            }

            $commentsSQL[] = $this->getCommentOnColumnSQL(
                $diff->getName($this)->getQuotedName($this),
                $column->getQuotedName($this),
                $this->getColumnComment($column)
            );
        }

        if (count($fields)) {
            $sql[] = 'ALTER TABLE ' . $diff->getName($this)->getQuotedName($this) . ' MODIFY (' . implode(', ', $fields) . ')';
        }

        foreach ($diff->renamedColumns as $oldColumnName => $column) {
            if ($this->onSchemaAlterTableRenameColumn($oldColumnName, $column, $diff, $columnSql)) {
                continue;
            }

            $oldColumnName = new Identifier($oldColumnName);

            $sql[] = 'ALTER TABLE ' . $diff->getName($this)->getQuotedName($this) .
                ' RENAME COLUMN ' . $oldColumnName->getQuotedName($this) . ' TO ' . $column->getQuotedName($this);
        }

        $fields = [];
        foreach ($diff->removedColumns as $column) {
            if ($this->onSchemaAlterTableRemoveColumn($column, $diff, $columnSql)) {
                continue;
            }

            $fields[] = $column->getQuotedName($this);
        }

        if (count($fields)) {
            $sql[] = 'ALTER TABLE ' . $diff->getName($this)->getQuotedName($this) . ' DROP (' . implode(', ', $f