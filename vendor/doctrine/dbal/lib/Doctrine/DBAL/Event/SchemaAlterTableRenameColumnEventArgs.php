rary'])) {
            $query .= 'TEMPORARY ';
        }

        $query .= 'TABLE ' . $tableName . ' (' . $queryFields . ') ';
        $query .= $this->buildTableOptions($options);
        $query .= $this->buildPartitionOptions($options);

        $sql    = [$query];
        $engine = 'INNODB';

        if (isset($options['engine'])) {
            $engine = strtoupper(trim($options['engine']));
        }

        // Propagate foreign key constraints only for InnoDB.
        if (isset($options['foreignKeys']) && $engine === 'INNODB') {
            foreach ((array) $options['foreignKeys'] as $definition) {
                $sql[] = $this->getCreateForeignKeySQL($definition, $tableName);
            }
        }

        return $sql;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultValueDeclarationSQL($field)
    {
        // Unset the default value if the given field definition does not allow default values.
        if ($field['type'] instanceof TextType || $field['type'] instanceof BlobType) {
            $field['default'] = null;
        }

        return parent::getDefaultValueDeclarationSQL($field);
    }

    /**
     * Build SQL for table options
     *
     * @param mixed[] $options
     *
     * @return string
     */
    private function buildTableOptions(array $options)
    {
        if (isset($options['table_options'])) {
            return $options['table_options'];
        }

        $tableOptions = [];

        // Charset
        if (! isset($options['charset'])) {
            $options['charset'] = 'utf8';
        }

        $tableOptions[] = sprintf('DEFAULT CHARACTER SET %s', $options['charset']);

        // Collate
        if (! isset($options['collate'])) {
            $options['collate'] = $options['charset'] . '_unicode_ci';
        }

        $tableOptions[] = sprintf('COLLATE %s', $options['collate']);

        // Engine
        if (! isset($options['engine'])) {
            $op