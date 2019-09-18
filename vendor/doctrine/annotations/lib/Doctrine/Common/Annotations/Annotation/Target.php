iative array containing column-value pairs.
     * @param mixed[]        $identifier      The update criteria. An associative array containing column-value pairs.
     * @param int[]|string[] $types           Types of the merged $data and $identifier arrays in that order.
     *
     * @return int The number of affected rows.
     *
     * @throws DBALException
     */
    public function update($tableExpression, array $data, array $identifier, array $types = [])
    {
        $setColumns = [];
        $setValues  = [];
        $set        = [];

        foreach ($data as $columnName => $value) {
            $setColumns[] = $columnName;
            $setValues[]  = $value;
            $set[]        = $columnName . ' = ?';
        }

        [$conditionColumns, $conditionValues, $conditions] = $this->gatherConditions($identifier);
        $columns                                           = array_merge($setColumns, $conditionColumns);
        $values                                            = array_merge($setValues, $conditionValues);

        if (is_string(key($types))) {
            $types = $this->extractTypeValues($columns, $types);
        }

        $sql = 'UPDATE ' . $tableExpression . ' SET ' . implode(', ', $set)
                . ' WHERE ' . implode(' AND ', $conditions);

        return $this->executeUpdate($sql, $values, $types);
    }

    /**
     * Inserts a table row with specified data.
     *
     * Table expression and columns are not escaped and are not safe for user-input.
     *
     * @param string         $tableExpression The expression of the table to insert data into, quoted or unquoted.
     * @param mixed[]        $data            An associative array containing column-value pairs.
     * @param int[]|string[] $types           Types of the inserted data.
     *
     * @return int The number of affected rows.
     *
     * @throws DBALException
     */
    public function insert($tableExpression, array $data, array $types = [])
    {
        if (empty($data)) {
            return $this->executeUpdate('INSERT INTO ' . $tableExpression . ' () VALUES ()');
        }

        $columns = [];
        $values  = [];
        $set     = [];

        foreach ($data as $columnName => $value) {
            $columns[] = $columnName;
            $values[]  = $value;
            $set[]     = '?';
        }

        return $this->executeUpdate(
            'INSERT INTO ' . $tableExpression . ' (' . implode(', ', $columns) . ')' .
            ' VALUES (' . implode(', ', $set) . ')',
            $values,
            is_string(key($types)) ? $this->extractTypeValues($columns, $types) : $types
        );
    }

    /**
     * Extract ordered type list from an ordered column list and type map.
     *
     * @param string[]       $columnList
     * @param int[]|string[] $types
     *
     * @return int[]|string[]
     */
    private function extractTypeValues(array $columnList, array $types)
    {
        $typeValues = [];

        foreach ($columnList as $columnIndex => $columnName) {
            $typeValues[] = $types[$columnName] ?? ParameterType::STRING;
        }

        return $typeValues;
    }

    /**
     * Quotes a string so it can be safely used as a table or column name, even if
     * it is a reserved name.
     *
