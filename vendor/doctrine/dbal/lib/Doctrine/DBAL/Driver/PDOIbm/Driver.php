mnDef)
    {
        $columnDef['precision'] = ! isset($columnDef['precision']) || empty($columnDef['precision'])
            ? 10 : $columnDef['precision'];
        $columnDef['scale']     = ! isset($columnDef['scale']) || empty($columnDef['scale'])
            ? 0 : $columnDef['scale'];

        return 'NUMERIC(' . $columnDef['precision'] . ', ' . $columnDef['scale'] . ')';
    }

    /**
     * Obtains DBMS specific SQL code portion needed to set a default value
     * declaration to be used in statements like CREATE TABLE.
     *
     * @param mixed[] $field The field definition array.
     *
     * @return string DBMS specific SQL code portion needed to set a default value.
     */
    public function getDefaultValueDeclarationSQL($field)
    {
        if (! isset($field['default'])) {
            return empty($field['notnull']) ? ' DEFAULT NULL' : '';
        }

        $default = $field['default'];

        if (! isset($field['type'])) {
            return " DEFAULT '" . $default . "'";
        }

        $type = $field['type'];

        if ($type instanceof Types\PhpIntegerMappingType) {
            return ' DEFAULT ' . $default;
        }

        if ($type instanceof Types\PhpDateTimeMappingType && $def