rn $list;
    }

    /**
     * {@inheritdoc}
     */
    protected function getPortableNamespaceDefinition(array $namespace)
    {
        return $namespace['nspname'];
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableSequenceDefinition($sequence)
    {
        if ($sequence['schemaname'] !== 'public') {
            $sequenceName = $sequence['schemaname'] . '.' . $sequence['relname'];
        } else {
            $sequenceName = $sequence['relname'];
        }

        if (! isset($sequence['increment_by'], $sequence['min_value'])) {
            /** @var string[] $data */
            $data = $this->_conn->fetchAssoc('SELECT min_value, increment_by FROM ' . $this->_platform->quoteIdentifier($sequenceName));

            $sequence += $data;
        }

        return new Sequence($sequenceName, (int) $sequence['increment_by'], (int) $sequence['min_value']);
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableTableColumnDefinition($tableColumn)
    {
        $tableColumn = array_change_key_case($tableColumn, CASE_LOWER);

        if (strtolower($tableColumn['type']) === 'varchar' || strtolower($tableColumn['type']) === 'bpchar') {
            // get length from varchar definition
            $length                = preg_replace('~.*\(([0-9]*)\).*~', '$1', $tableColumn['complete_type']);
            $tableColumn['length'] = $length;
        }

        $matches = [];

        $autoincrement = false;
        if (preg_match("/^nextval\('(.*)'(::.*)?\)$/", $tableColumn['default'], $matches)) {
            $tableColumn['sequence'] = $matches[1];
            $tableColumn['default']  = null;
            $autoincrement           = true;
        }

        if (preg_match("/^['(](.*)[')]::.*$/", $tableColumn['default'], $matches)) {
            $tableColumn['default'] = $matches[1];
        }

        if (stripos($tableColumn['default'], 'NULL') === 0) {
            $tableColumn['default'] = null;
        }

        $length = $tableColumn['length'] ?? null;
        if ($length === '-1' && isset($tableColumn['atttypmod'])) {
            $length = $tableColumn['atttypmod'] - 4;
        }
        if ((int) $length <= 0) {
            $length = null;
        }
        $fixed = null;

        if (! isset($tableColumn['name'])) {
            $tableColumn['name'] = '';
        }

        $precision = null;
        $scale     = null;
        $jsonb     = null;

        $dbType = strtolower($tableColumn['type']);
        if (strlen($tableColumn['domain_type']) && ! $this->_platform->hasDoctrineTypeMappingFor($tableColumn['type'])) {
            $dbType                       = strtolower($tableColumn['domain_type']);
            $tableColumn['complete_type'] = $tableColumn['domain_complete_type'];
        }

        $type                   = $this->_platform->getDoctrineTypeMapping($dbType);
        $type                   = $this->extractDoctrineTypeFromComment($tableColumn['comment'], $type);
        $tableColumn['comment'] = $this->removeDoctrineTypeFromComment($tableColumn['comment'], $type);

        switch ($dbType) {
            case 'smallint':
            case 'int2':
                $tableColumn['default'] = $this->fixVersion94NegativeNumericDefaultValue($tableColumn['default']);
                $length                 = null;
                break;
            case 'int':
            case 'int4':
            case 'integer':
                $tableColumn['default'] = $this->fixVersion94NegativeNumericDefaultValue($tableColumn['default']);
                $length                 = null;
                break;
            case 'bigint':
            case 'int8':
                $tableColumn['default'] = $this->fixVersion94NegativeNumericDefaultValue($tableColumn['default']);
                $length                 = null;
                break;
            case 'bool':
            case 'boolean':
                if ($tableColumn['default'] === 'true') {
                    $tableColumn['default'] = true;
                }

                if ($tableColumn['default'] === 'false') {
                    $tableColumn['default'] = false;
                }

                $length = null;
                break;
            case 'text':
                $fixed = false;
                break;
            case 'varchar':
            case 'interval':
            case '_varchar':
                $fixed = false;
                break;
            case 'char':
            case 'bpchar':
                $fixed = tru