 {@inheritDoc}
     */
    protected function _getCreateTableSQL($tableName, array $columns, array $options = [])
    {
        $queryFields = $this->getColumnDeclarationListSQL($columns);

        if (isset($options['uniqueConstraints']) && ! empty($options['uniqueConstraints'])) {
            foreach ($options['uniqueConstraints'] as $index => $definition) {
                $queryFields .= ', ' . $this->getUniqueConstraintDeclarationSQL($index, $definition);
            }
        }

        // add all indexes
        if (isset($options['indexes']) && ! empty($options['indexes'])) {
            foreach ($options['indexes'] as $index => $definition) {
                $queryFields .= ', ' . $this->getIndexDeclarationSQL($index, $definition);
            }
        }

        // attach all primary keys
        if (isset($options['primary']) && ! empty($options['primary'])) {
            $keyColumns   = array_unique(array_values($options['primary']));
            $queryFields .= ', PRIMARY KEY(' . implode(', ', $keyColumns) . ')';
        }

        $query = 'CREATE ';

        if (! empty($options['temporary'])) {
            $query .= 'TEMPORARY '