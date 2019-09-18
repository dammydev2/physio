f, $diff, $columnSql)) {
                continue;
            }

            $column      = $columnDiff->column;
            $columnArray = $column->toArray();

            // Don't propagate default value changes for unsupported column types.
            if ($columnDiff->hasChanged('default') &&
                count($columnDiff->changedProperties) === 1 &&
                ($columnArray['type'] instanceof TextType || $columnArray['type'] instanceof BlobType)
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

            $oldColumnName          = new Identifier($oldColumnName);
            $columnArray            = $column->toArray();
            $columnArray['comment'] = $this->getColumnComment($column);
            $queryParts[]           =  'CHANGE ' . $oldColumnName->getQuotedName($this) . ' '
                    . $this->getColumnDeclarationSQL($column->getQuotedName($this), $columnArray);
        }

        if (isset($diff->addedIndexes['primary'])) {
            $keyColumns   = array_unique(array_values($diff->addedIndexes['primary']->getColumns()));
            $queryParts[] = 'ADD PRIMARY KEY (' . implode(', ', $keyColumns) . ')';
            unset($diff->addedIndexes['primary']);
        }

        $sql      = [];
        $tableSql = [];

        if (! $this->onSchemaAlterTable($diff, $tableSql)) {
            if (count($queryParts) > 0) {
                $sql[] = 'ALTER TABLE ' . $diff->getName($this)->getQuotedName($this) . ' ' . implode(', ', $queryParts);
            }
            $sql = array_merge(
                $this->getPreAlterTableIndexForeignKeySQL($diff),
      