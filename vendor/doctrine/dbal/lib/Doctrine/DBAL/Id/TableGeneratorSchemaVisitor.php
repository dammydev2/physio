n 'oracle';
    }

    /**
     * {@inheritDoc}
     */
    protected function doModifyLimitQuery($query, $limit, $offset = null)
    {
        if ($limit === null && $offset <= 0) {
            return $query;
        }

        if (preg_match('/^\s*SELECT/i', $query)) {
            if (! preg_match('/\sFROM\s/i', $query)) {
                $query .= ' FROM dual';
            }

            $columns = ['a.*'];

            if ($offset > 0) {
                $columns[] = 'ROWNUM AS doctrine_rownum';
            }

            $query = sprintf('SELECT %s FROM (%s) a', implode(', ', $columns), $query);

            if ($limit !== null) {
                $query .= sprintf(' WHERE ROWNUM <= %d', $offset + $limit);
            }

            if ($offset > 0) {
                $query = sprintf('SELECT * FROM (%s) WHERE doctrine_rownum >= %d', $query, $offset + 1);
            }
        }

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * Oracle returns all column names in SQL result sets in uppercase.
     */
    public function getSQLResultCasing($column)
    {
        return strtoupper($column);
    }

    /**
     * {@inheritDoc}
     */
    public function getCreateTemporaryTableSnippetSQL()
    {
        return 'CREATE GLOBAL TEMPORARY TABLE';
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTimeTzFormatString()
    {
        return 'Y-m-d H:i:sP';
    }

    /**
     * {@inheritDoc}
     */
    public function getDateFormatString()
    {
        return 'Y-m-d 00:00:00';