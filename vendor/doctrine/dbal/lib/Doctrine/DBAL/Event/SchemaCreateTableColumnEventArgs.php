    $diff->addedForeignKeys   = [];
            $diff->changedForeignKeys = [];
            $diff->removedForeignKeys = [];
        }

        $sql = array_merge(
            $sql,
            $this->getPreAlterTableAlterIndexForeignKeySQL($diff),
            parent::getPreAlterTableIndexForeignKeySQL($diff),
            $this->getPreAlterTableRenameIndexForeignKeySQL($diff)
        );

        return $sql;
    }

    /**
     * @return string[]
     */
    private function getPreAlterTableAlterPrimaryKeySQL(TableDiff $diff, Index $index)
    {
        $sql = [];

        if (! $index->isPrimary() || ! $diff->fromTable instanceof Table) {
            return $sql;
        }

        $tableName = $diff->getName($this)->getQuotedName($this);

        // Dropping primary keys requires to unset autoincrement attribute on the particular column first.
        foreach ($index->getColumns() as $columnName) {
            if (! $diff->fromTable->hasColumn($columnName)) {
                continue;
            }

            $column = $diff->fromTable->getColumn($columnName);

            if ($column->getAutoincrement() !== true) {
                continue;
            }

            $column->setAutoincrement(false);

            $sql[] = 'ALTER TABLE ' . $tableName . ' MODIFY ' .
                $this->getColumnDeclarationSQL($column->getQuotedName($this), $column->toArray());

            // original autoincrement information might be needed later on by other parts of the table alteration
            $column->setAutoincrement(true);
        }

        return $sql;
    }

    /**
     * @param