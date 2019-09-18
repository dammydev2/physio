eyConstraint[]
     */
    private function getRemainingForeignKeyConstraintsRequiringRenamedIndexes(TableDiff $diff)
    {
        if (empty($diff->renamedIndexes) || ! $diff->fromTable instanceof Table) {
            return [];
        }

        $foreignKeys = [];
        /** @var ForeignKeyConstraint[] $remainingForeignKeys */
        $remainingForeignKeys = array_diff_key(
            $diff->fromTable->getForeignKeys(),
            $diff->removedForeignKeys
        );

        foreach ($remainingForeignKeys as $foreignKey) {
            foreach ($diff->renamedIndexes as $index) {
                if ($foreignKey->intersectsIndexColumns($index)) {
                    $foreignKeys[] = $foreignKey;

                    break;
                }
            }
        }

        return $foreignKeys;
    }

    /**
     * {@inheritdoc}
     */
    protected function getPostAlterTableIndexForeignKeySQL(TableDiff $diff)
    {
        return array_merge(
            parent::getPostAlterTableIndexForeignKeySQL($diff),
            $this->getPostAlterTableRenameIndexForeignKeySQL($diff)
        );
    }

    /**
     * @param TableDiff $diff The table diff to gather the SQL for.
     *
     * @return string[]
     */
    protected function getPostAlterTableRenameIndexForeignKeySQL(TableDiff $diff)
    {
        $sql       = [];
        $tableName = $diff->newName !== false
            ? $diff->getNewName()->getQuotedName($this)
            : $diff->getName($this)->getQuotedName($this);

        foreach ($this->getRemainingForeignKeyConstraintsRequiringRenamedIndexes($diff) as $foreignKey) {
            if (in_array($foreignKey, $diff->changedForeignKeys, true)) {
                continue;
            }

            $sql[] = $this->getCreateForeignKeySQL($foreignKey, $tableName);
        }

        return $sql;
    }

    /**
     * {@inheritDoc}
     */
    protected f