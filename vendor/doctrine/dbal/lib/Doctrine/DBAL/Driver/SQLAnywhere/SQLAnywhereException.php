abschema = cols.tabschema
               AND subq.tabname = cols.tabname
               AND subq.colno = cols.colno
        ORDER BY subq.colno
        ';
    }

    /**
     * {@inheritDoc}
     */
    public function getListTablesSQL()
    {
        return "SELECT NAME FROM SYSIBM.SYSTABLES WHERE TYPE = 'T'";
    }

    /**
     * {@inheritDoc}
     */
    public function getListViewsSQL($database)
    {
        return 'SELECT NAME, TEXT FROM SYSIBM.SYSVIEWS';
    }

    /**
     * {@inheritDoc}
     */
    public function getListTableIndexesSQL($table, $currentDatabase = null)
    {
        $table = $this->quoteStringLiteral($table);

        return "SELECT   idx.INDNAME AS key_name,
                         idxcol.COLNAME AS column_name,
                         CASE
                             WHEN idx.UNIQUERULE = 'P' THEN 1
                             ELSE 0
                         END AS primary,
                         CASE
                             WHEN idx.UNIQUERULE = 'D' THEN 1
                             ELSE 0
                         END AS non_unique
                FROM     SYSCAT.INDEXES AS idx
                JOIN     SYSCAT.INDEXCOLUSE AS idxcol
                ON       idx.INDSCHEMA = idxcol.INDSCHEMA AND idx.INDNAME = idxcol.INDNAME
                WHERE    idx.TABNAME = UPPER(" . $table . ')
                ORDER BY idxcol.COLSEQ ASC';
    }

    /**
     * {@inheritDoc}
     */
    public function getListTableForeignKeysSQL($table)
    {
        $table = $this->quoteStringLiteral($table);

        return "SELECT   fkcol.COLNAME AS local_column,
                         fk.REFTABNAME AS foreign_table,
                         pkcol.COLNAME AS foreign_column,
                         fk.CONSTNAME AS index_name,
                         CASE
                             WHEN fk.UPDATERULE = 'R' THEN 'RESTRICT'
                             ELSE NULL
                         END AS on_update,
                         CASE
                             WHEN fk.DELETERULE = 'C' THEN 'CASCADE'
                             WHEN fk.DELETERULE = 'N' THEN 'SET NULL'
                             WHEN fk.DELETERULE = 'R' THEN 'RESTRICT'
                             ELSE NULL
                         END AS on_delete
                FROM     SYSCAT.REFERENCES AS fk
                JOIN     SYSCAT.KEYCOLUSE AS fkcol
                ON       fk.CONSTNAME = fkcol.CONSTNAME
                AND      fk.TABSCHEMA = fkcol.TABSCHEMA
                AND      fk.TABNAME = fkcol.TABNAME
                JOIN     SYSCAT.KEYCOLUSE AS pkcol
                ON       fk.REFKEYNAME = pkcol.CONSTNAME
                AND      fk.REFTABSCHEMA = pkcol.TABSCHEMA
                AND      fk.REFTABNAME = p