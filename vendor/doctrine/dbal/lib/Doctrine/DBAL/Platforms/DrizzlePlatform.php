ue);

            $sql = $this->getPreAlterTableIndexForeignKeySQL($diff);
            //$sql = array_merge($sql, $this->getCreateTableSQL($dataTable, 0));
            $sql[] = sprintf('CREATE TEMPORARY TABLE %s AS SELECT %s FROM %s', $dataTable->getQuotedName($this), implode(', ', $oldColumnNames), $table->getQuotedName($this));
            $sql[] = $this->getDropTableSQL($fromTable);

            $sql   = array_merge($sql, $this->getCreateTableSQL($newTable));
            $sql[] = sprintf('INSERT INTO %s (%s) SELECT %s FROM %s', $newTable->getQuotedName($this), implode(', ', $newColumnNames), implode(', ', $oldColumnNames), $dataTable->getQuotedName($this));
            $sql[] = $this->getDropTableSQL($dataTable);

            if ($diff->newName && $diff->newName !== $diff->name) {
                $renamedTable = $diff->getNewName();
                $sql[]        = 'ALTER TABLE ' . $newTable->getQuotedName($this) . ' RENAME TO ' . $renamedTable->getQuotedName($this);
            }

            $sql = array_merge($sql, $this->getPostAlterTableIndexForeignKeySQL($diff));
        }

        return array_merge($sql, $tableSql, $columnSql);
    }

    /**
     * @return string[]|false
     */
    private function getSimpleAlterTableSQL(TableDiff $diff)
    {
        // Suppress changes on integer type autoincrement columns.
        foreach ($diff->changedColumns as $oldColumnName => $columnDiff) {
            if (! $columnDiff->fromColumn instanceof Column ||
                ! $columnDiff->column instanceof Column ||
                ! $columnDiff->column->getAutoincrement() ||
                ! $columnDiff->column->getType() instanceof Types\IntegerType
            ) {
                continue;
            }

            if (! $columnDiff->hasChanged('type') && $columnDiff->hasChanged('unsigned')) {
                unset($diff->changedColumns[$oldColumnName]);

                continue;
            }

            $fromColumnType = $columnDiff->fromColumn->getType();

            if (! ($fromColumnType instanceof Types\SmallIntType) && ! ($fromColumnType instanceof Types\BigIntType)) {
                continue;
            }

            unset($diff->changedColumns[$oldColumnName]);
        }

        if (! empty($diff->renamedColumns) || ! empty($diff->addedForeignKeys) || ! empty($diff->addedIndexes)
                || ! empty($diff->changedColumns) || ! empty($diff->changedForeignKeys) || ! empty($diff->changedIndexes)
                || ! empty($diff->removedColumns) || ! empty($diff->removedForeignKeys) || ! empty($diff->removedIndexes)
                || ! empty($diff->renamedIndexes)
        ) {
            return false;
        }

        $table = new Table($diff->name);

        $sql       = [];
        $tableSql  = [];
        $columnSql = [];

        foreach ($diff->addedColumns as $column) {
            if ($this->onSchemaAlterTableAddColumn($column, $diff, $columnSql)) {
                continue;
            }

            $field = array_merge(['unique' => null, 'autoincrement' => null, 'default' => null], $column->toArray());
            $type  = $field['type'];
            switch (true) {
                case isset($field['columnDefinition']) || $field['autoincrement'] || $field['unique']:
                case $type instanceof Types\DateTimeType && $field['default'] === $this->getCurrentTimestampSQL():
                case $type instanceof Types\DateType && $field['default'] === $this->getCurrentDateSQL():
                case $type instanceof Types\TimeType && $field['default'] === $this->getCurrentTimeSQL():
                    return false;
            }

            $field['name'] = $column->getQuotedName($this);
            if ($type instanceof Types\StringType && $field['length'] === null) {
                $field['length'] = 255;
            }

            $sql[] = 'ALTER TABLE ' . $table->getQuotedName($this) . ' ADD COLUMN ' . $this->getColumnDeclarationSQL($field['name'], $field);
        }

        if (! $this->onSchemaAlterTable($diff, $tableSql)) {
            if ($diff->newName !== false) {
                $newTable = new Identifier($diff->newName);
                $sql[]    = 'ALTER TABLE ' . $table->getQuotedName($this) . ' RENAME TO ' . $newTable->getQuotedName($this);
            }
        }

        return array_merge($sql, $tableSql, $columnSql);
    }

    /**
     * @return string[]
     */
    private function getColumnNamesInAlteredTable(TableDiff $diff)
    {
        $columns = [];

        foreach ($diff->fromTable->getColumns() as $columnName => $column) {
            $columns[strtolower($columnName)] = $column->getName();
        }

        foreach ($diff->removedColumns as $columnName => $column) {
            $columnName = strtolower($columnName);
            if (! isset($columns[$columnName])) {
                continue;
            }

            unset($columns[$columnName]);
        }

        foreach ($diff->renamedColumns as $oldColumnName => $column) {
            $columnName                          = $column->getName();
            $columns[strtolower($oldColumnName)] = $columnName;
            $columns[strtolower($columnName)]    = $columnName;
        }

        foreach ($diff->changedColumns as $oldColumnName => $columnDiff) {
            $columnName                          = $columnDiff->column->getName();
            $columns[strtolower($oldColumnName)] = $columnName;
            $columns[strtolower($columnName)]    = $columnName;
        }

        foreach ($diff->addedColumns as $columnName => $column) {
            $columns[strtolower($columnName)] = $columnName;
        }

        return $columns;
    }

    /**
     * @return Index[]
     */
    private function getIndexesInAlteredTable(TableDiff $diff)
    {
        $indexes     = $diff->fromTable->getIndexes();
        $columnNames = $this->getColumnNamesInAlteredTable($diff);

        foreach ($indexes as $key => $index) {
            foreach ($diff->renamedIndexes as $oldIndexName => $renamedIndex) {
                if (strtolower($key) !== strtolower($oldIndexName)) {
                    continue;
                }

                unset($indexes[$key]);
            }

            $changed      = false;
            $indexColumns = [];
            foreach ($index->getColumns() as $columnName) {
                $normalizedColumnName = strtolower($columnName);
                if (! isset($columnNames[$normalizedColumnName])) {
                    unset($indexes[$key]);
                    continue 2;
                } else {
                    $indexColumns[] = $columnNames[$normalizedColumnName];
                    if ($columnName !== $columnNames[$normalizedColumnName]) {
                        $changed = true;
                    }
                }
            }

            if (! $changed) {
                continue;
            }

            $indexes[$key] = new Index($index->getName(), $indexColumns, $index->isUnique(), $index->isPrimary(), $index->getFlags());
        }

        foreach ($diff->removedIndexes as $index) {
            $indexName = strtolower($index->getName());
            if (! strlen($indexName) || ! isset($indexes[$indexName])) {
                continue;
            }

            unset($indexes[$indexName]);
        }

        foreach (array_merge($diff->changedIndexes, $diff->addedIndexes, $diff->renamedIndexes) as $index) {
            $indexName = strtolower($index->getName());
            if (strlen($indexName)) {
                $indexes[$indexName] = $index;
            } else {
                $indexes[] = $index;
            }
        }

        return $indexes;
    }

    /**
     * @return ForeignKeyConstraint[]
     */
    private function getForeignKeysInAlteredTable(TableDiff $diff)
    {
        $foreignKeys = $diff->fromTable->getForeignKeys();
        $columnNames = $this->getColumnNamesInAlteredTable($diff);

        foreach ($foreignKeys as $key => $constraint) {
            $changed      = false;
            $localColumns = [];
            foreach ($constraint->getLocalColumns() as $columnName) {
                $normalizedColumnName = strtolower($columnName);
                if (! isset($columnNames[$normalizedColumnName])) {
                    unset($foreignKeys[$key]);
                    continue 2;
                } else {
                    $localColumns[] = $columnNames[$normalizedColumnName];
                    if ($columnName !== $columnNames[$normalizedColumnName]) {
                        $changed = true;
                    }
                }
            }

            if (! $changed) {
                continue;
            }

            $foreignKeys[$key] = new ForeignKeyConstraint($localColumns, $constraint->getForeignTableName(), $constraint->getForeignColumns(), $constraint->getName(), $constraint->getOptions());
        }

        foreach ($diff->removedForeignKeys as $constraint) {
            $constraintName = strtolower($constraint->getName());
            if (! strlen($constraintName) || ! isset($foreignKeys[$constraintName])) {
                continue;
            }

            unset($foreignKeys[$constraintName]);
        }

        foreach (array_merge($diff->changedForeignKeys, $diff->addedForeignKeys) as $constraint) {
            $constraintName = strtolower($constraint->getName());
            if (strlen($constraintName)) {
                $foreignKeys[$constraintName] = $constraint;
            } else {
                $foreignKeys[] = $constraint;
            }
        }

        return $foreignKeys;
    }

    /**
     * @return Index[]
     */
    private function getPrimaryIndexInAlteredTable(TableDiff $diff)
    {
        $primaryIndex = [];

        foreach ($this->getIndexesInAlteredTable($diff) as $index) {
            if (! $index->isPrimary()) {
                continue;
            }

            $primaryIndex = [$index->getName() => $index];
        }

        return $primaryIndex;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <?php

namespace Doctrine\DBAL\Platforms;

/**
 * Platform to ensure compatibility of Doctrine with Microsoft SQL Server 2005 version and
 * higher.
 *
 * Differences to SQL Server 2008 are:
 *
 * - DATETIME2 datatype does not exist, only DATETIME which has a precision of
 *   3. This is not supported by PHP DateTime, so we are emulating it by
 *   setting .000 manually.
 * - Starting with SQLServer2005 VARCHAR(MAX), VARBINARY(MAX) and
 *   NVARCHAR(max) replace the old TEXT, NTEXT and IMAGE types. See
 *   {@link http://www.sql-server-helper.com/faq/sql-server-2005-varchar-max-p01.aspx}
 *   for more information.
 */
class SQLServer2005Platform extends SQLServerPlatform
{
    /**
     * {@inheritDoc}
     */
    public function supportsLimitOffset()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getClobTypeDeclarationSQL(array $field)
    {
        return 'VARCHAR(MAX)';
    }

    /**
     * {@inheritdoc}
     *
     * Returns Microsoft SQL Server 2005 specific keywords class
     */
    protected function getReservedKeywordsClass()
    {
        return Keywords\SQLServer2005Keywords::class;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     <?php

namespace Doctrine\DBAL\Platforms;

/**
 * Platform to ensure compatibility of Doctrine with Microsoft SQL Server 2008 version.
 *
 * Differences to SQL Server 2005 and before are that a new DATETIME2 type was
 * introduced that has a higher precision.
 */
class SQLServer2008Platform extends SQLServer2005Platform
{
    /**
     * {@inheritDoc}
     */
    public function getListTablesSQL()
    {
        // "sysdiagrams" table must be ignored as it's internal SQL Server table for Database Diagr