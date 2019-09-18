) {
                        continue;
                    }
                    unset($diff->changedTables[$localTableName]->removedForeignKeys[$key]);
                }
            }
        }

        foreach ($toSchema->getSequences() as $sequence) {
            $sequenceName = $sequence->getShortestName($toSchema->getName());
            if (! $fromSchema->hasSequence($sequenceName)) {
                if (! $this->isAutoIncrementSequenceInSchema($fromSchema, $sequence)) {
                    $diff->newSequences[] = $sequence;
                }
            } else {
                if ($this->diffSequence($sequence, $fromSchema->getSequence($sequenceName))) {
                    $diff->changedSequences[] = $toSchema->getSequence($sequenceName);
                }
            }
        }

        foreach ($fromSchema->getSequences() as $sequence) {
            if ($this->isAutoIncrementSequenceInSchema($toSchema, $sequence)) {
                continue;
            }

            $sequenceName = $sequence->getShortestName($fromSchema->getName());

            if ($toSchema->hasSequence($sequenceName)) {
                continue;
            }

            $diff->removedSequences[] = $sequence;
        }

        return $diff;
    }

    /**
     * @param Schema   $schema
     * @param Sequence $sequence
     *
     * @return bool
     */
    private function isAutoIncrementSequenceInSchema($schema, $sequence)
    {
        foreach ($schema->getTables() as $table) {
            if ($sequence->isAutoIncrementsFor($table)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function diffSequence(Sequence $sequence1, Sequence $sequence2)
    {
        if ($sequence1->getAllocationSize() !== $sequence2->getAllocationSize()) {
            return true;
        }

        return $sequence1->getInitialValue() !== $sequence2->getInitialValue();
    }

    /**
     * Returns the difference between the tables $table1 and $table2.
     *
     * If there are no differences this method returns the boolean false.
     *
     * @return TableDiff|false
     */
    public function diffTable(Table $table1, Table $table2)
    {
        $changes                     = 0;
        $tableDifferences            = new TableDiff($table1->getName());
        $tableDifferences->fromTable = $table1;

        $table1Columns = $table1->getColumns();
        $table2Columns = $table2->getColumns();

        /* See if all the fields in table 1 exist in table 2 */
        foreach ($table2Columns as $columnName => $column) {
            if ($table1->hasColumn($columnName)) {
                continue;
            }

            $tableDifferences->addedColumns[$columnName] = $column;
            $changes++;
        }
        /* See if there are any removed fields in table 2 */
        foreach ($table1Columns as $columnName => $column) {
            // See if column is removed in table 2.
            if (! $table2->hasColumn($columnName)) {
                $tableDifferences->removedColumns[$columnName] = $column;
                $changes++;
                continue;
            }

            // See if column has changed properties in table 2.
            $changedProperties = $this->diffColumn($column, $table2->getColumn($columnName));

            if (empty($changedProperties)) {
                continue;
            }

            $columnDiff                                           = new ColumnDiff($column->getName(), $table2->getColumn($columnName), $changedProperties);
            $columnDiff->fromColumn                               = $column;
            $tableDifferences->changedColumns[$column->getName()] = $columnDiff;
            $changes++;
        }

        $this->detectColumnRenamings($tableDifferences);

        $table1Indexes = $table1->getIndexes();
        $table2Indexes = $table2->getIndexes();

        /* See if all the indexes in table 1 exist in table 2 */
        foreach ($table2Indexes as $indexName => $index) {
            if (($index->isPrimary() && $table1->hasPrimaryKey()) || $table1->hasIndex($indexName)) {
                continue;
            }

            $tableDifferences->addedIndexes[$indexName] = $index;
            $changes++;
        }
        /* See if there are any removed indexes in table 2 */
        foreach ($table1Indexes as $indexName => $index) {
            // See if index is removed in table 2.
            if (($index->isPrimary() && ! $table2->hasPrimaryKey()) ||
                ! $index->isPrimary() && ! $table2->hasIndex($indexName)
            ) {
                $tableDifferences->removedIndexes[$indexName] = $index;
                $changes++;
                continue;
            }

            // See if index has changed in table 2.
            $table2Index = $index->isPrimary() ? $table2->getPrimaryKey() : $table2->getIndex($indexName);

            if (! $this->diffIndex($index, $table2Index)) {
                continue;
            }

            $tableDifferences->changedIndexes[$indexName] = $table2Index;
            $changes++;
        }

        $this->detectIndexRenamings($tableDifferences);

        $fromFkeys = $table1->getForeignKeys();
        $toFkeys   = $table2->getForeignKeys();

        foreach ($fromFkeys as $key1 => $constraint1) {
            foreach ($toFkeys as $key2 => $constraint2) {
                if ($this->diffForeignKey($constraint1, $constraint2) === false) {
                    unset($fromFkeys[$key1], $toFkeys[$key2]);
                } else {
                    if (strtolower($constraint1->getName()) === strtolower($constraint2->getName())) {
                        $tableDifferences->changedForeignKeys[] = $constraint2;
                        $changes++;
                        unset($fromFkeys[$key1], $toFkeys[$key2]);
                    }
                }
            }
        }

        foreach ($fromFkeys as $constraint1) {
            $tableDifferences->removedForeignKeys[] = $constraint1;
            $changes++;
        }

        foreach ($toFkeys as $constraint2) {
            $tableDifferences->addedForeignKeys[] = $constraint2;
            $changes++;
        }

        return $changes ? $tableDifferences : false;
    }

    /**
     * Try to find columns that only changed their name, rename operations maybe cheaper than add/drop
     * however ambiguities between different possibilities should not lead to renaming at all.
     *
     * @return void
     */
    private function detectColumnRenamings(TableDiff $tableDifferences)
    {
        $renameCandidates = [];
        foreach ($tableDifferences->addedColumns as $addedColumnName => $addedColumn) {
            foreach ($tableDifferences->removedColumns as $removedColumn) {
                if (count($this->diffColumn($addedColumn, $removedColumn)) !== 0) {
                    continue;
                }

                $renameCandidates[$addedColumn->getName()][] = [$removedColumn, $addedColumn, $addedColumnName];
            }
        }

        foreach ($renameCandidates as $candidateColumns) {
            if (count($candidateColumns) !== 1) {
                continue;
      