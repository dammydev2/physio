   /**
     * {@inheritDoc}
     */
    public function getBooleanTypeDeclarationSQL(array $field)
    {
        return 'BIT';
    }

    /**
     * {@inheritDoc}
     */
    protected function doModifyLimitQuery($query, $limit, $offset = null)
    {
        $where = [];

        if ($offset > 0) {
            $where[] = sprintf('doctrine_rownum >= %d', $offset + 1);
        }

        if ($limit !== null) {
            $where[] = sprintf('doctrine_rownum <= %d', $offset + $limit);
            $top     = sprintf('TOP %d', $offset + $limit);
        } else {
            $top = 'TOP 9223372036854775807';
        }

        if (empty($where)) {
            return $query;
        }

        // We'll find a SELECT or SELECT distinct and prepend TOP n to it
        // Even if the TOP n is very large, the use of a CTE will
        // allow the SQL Server query planner to optimize it so it doesn't
        // actually scan the entire range covered by the TOP clause.
        $selectPattern  = '/^(\s*SELECT\s+(?:DISTINCT\s+)?)(.*)$/im';
        $replacePattern = sprintf('$1%s $2', $top);
        $query          = preg_replace($selectPattern, $replacePattern, $query);

        if (stristr($query, 'ORDER BY')) {
            // Inner order by is not valid in SQL Server for our purposes
            // unless it's in a TOP N subquery.
            $query = $this->scrubInnerOrderBy($query);
        }

        // Build a new limited query around the original, using a CTE
        return sprintf(
            'WITH dctrn_cte AS (%s) '
            . 'SELECT * FROM ('
            . 'SELECT *, ROW_NUMBER() OVER (ORDER BY (SELECT 0)) AS doctrine_rownum FROM dctrn_cte'
            . ') AS doctrine_tbl '
            . 'WHERE %s ORDER BY doctrine_rownum ASC',
            $query,
            implode(' AND ', $where)
        );
    }

    /**
     * Remove ORDER BY clauses in subqueries - they're not supported by SQL Server.
     * Caveat: will leave ORDER BY in TOP N subqueries.
     *
     * @param string $query
     *
     * @return string
     */
    private function scrubInnerOrderBy($query)
    {
        $count  = substr_count(strtoupper($query), 'ORDER BY');
        $offset = 0;

        while ($count-- > 0) {
            $orderByPos = stripos($query, ' ORDER BY', $offset);
            if ($orderByPos === false) {
                break;
            }

            $qLen            = strlen($query);
            $parenCount      = 0;
            $currentPosition = $orderByPos;

            while ($parenCount >= 0 && $currentPosition < $qLen) {
                if ($query[$currentPosition] === '(') {
                    $parenCount++;
                } elseif ($query[$currentPosition] === ')') {
                    $parenCount--;
                }

                $currentPosition++;
            }

            if ($this->isOrderByInTopNSubquery($query, $orderByPos)) {
                // If the order by clause is in a TOP N subquery, do not remove
                // it and continue iteration from the current position.
                $offset = $currentPosition;
                continue;
            }

            if ($currentPosition >= $qLen - 1) {
                continue;
            }

            $query  = substr($query, 0, $orderByPos) . substr($query, $currentPosition - 1);
            $offset = $orderByPos;
        }
        return $query;
    }

    /**
     * Check an ORDER BY clause to see if it is in a TOP N query or subquery.
     *
     * @param string $query           The query
     * @param int    $currentPosition Start position of ORDER BY clause
     *
     * @return bool true if ORDER BY is in a TOP N query, false otherwise
     */
    private function isOrderByInTopNSubquery($query, $currentPosition)
    {
        // Grab query text on the same nesting level as the ORDER BY clause we're examining.
        $subQueryBuffer = '';
        $parenCount     = 0;

        // If $parenCount goes negative, we've exited the subquery we're examining.
        // If $currentPosition goes negative, we've reached the beginning of the query.
        while ($parenCount >= 0 && $currentPosition >= 0) {
            if ($query[$currentPosition] === '(') {
                $parenCount--;
            } elseif ($query[$currentPosition] === ')') {
                $parenCount++;
            }

            // Only yank query text on the same nesting level as the ORDER BY clause.
            $subQueryBuffer = ($parenCount === 0 ? $query[$currentPosition] : ' ') . $subQueryBuffer;

            $currentPosition--;
        }

        return (bool) preg_match('/SELECT\s+(DISTINCT\s+)?TOP\s/i', $subQueryBuffer);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsLimitOffset()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function convertBooleans($item)
    {
        if (is_array($item)) {
            foreach ($item as $key => $value) {
                if (! is_bool($value) && ! is_numeric($item)) {
                    continue;
                }

                $item[$key] = $value ? 1 : 0;
            }
        } elseif (is_bool($item) || is_numeric($item)) {
            $item = $item ? 1 : 0;
        }

        return $item;
    }

    /**
     * {@inheritDoc}
     */
    public function getCreateTemporaryTableSnippetSQL()
    {
        return 'CREATE TABLE';
    }

    /**
     * {@inheritDoc}
     */
    public function getTemporaryTableName($tableName)
    {
        return '#' . $tableName;
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTimeFormatString()
    {
        return 'Y-m-d H:i:s.000';
    }

    /**
     * {@inheritDoc}
     */
    public function getDateFormatString()
    {
        return 'Y-m-d H:i:s.000';
    }

    /**
     * {@inheritDoc}
     */
    public function getTimeFormatString()
    {
        return 'Y-m-d H:i:s.000';
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTimeTzFormatString()
    {
        return $this->getDateTimeFormatString();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'mssql';
    }

    /**
     * {@inheritDoc}
     */
    protected function initializeDoctrineTypeMappings()
    {
        $this->doctrineTypeMapping = [
            'bigint' => 'bigint',
            'numeric' => 'decimal',
            'bit' => 'boolean',
            'smallint' => 'smallint',
            'decimal' => 'decimal',
            'smallmoney' => 'integer',
            'int' => 'integer',
            'tinyint' => 'smallint',
            'money' => 'integer',
            'float' => 'float',
            'real' => 'float',
            'double' => 'float',
            'double precision' => 'float',
            'smalldatetime' => 'datetime',
            'datetime' => 'datetime',
            'char' => 'string',
            'varchar' => 'string',
            'text' => 'text',
            'nchar' => 'string',
            'nvarchar' => 'string',
            'ntext' => 'text',
            'binary' => 'binary',
            'varbinary' => 'binary',
            'image' => 'blob',
            'uniqueidentifier' => 'guid',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function createSavePoint($savepoint)
    {
        return 'SAVE TRANSACTION ' . $savepoint;
    }

    /**
     * {@inheritDoc}
     */
    public function releaseSavePoint($savepoint)
    {
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function rollbackSavePoint($savepoint)
    {
        return 'ROLLBACK TRANSACTION ' . $savepoint;
    }

    /**
     * {@inheritdoc}
     */
    public function getForeignKeyReferentialActionSQL($action)
    {
        // RESTRICT is not supported, therefore falling back to NO ACTION.
        if (strtoupper($action) === 'RESTRICT') {
            return 'NO ACTION';
        }

        return parent::getForeignKeyReferentialActionSQL($action);
    }

    /**
     * {@inheritDoc}
     */
    public function appendLockHint($fromClause, $lockMode)
    {
        switch (true) {
            case $lockMode === LockMode::NONE:
                return $fromClause . ' WITH (NOLOCK)';

            case $lockMode === LockMode::PESSIMISTIC_READ:
                return $fromClause . ' WITH (HOLDLOCK, ROWLOCK)';

            case $lockMode === LockMode::PESSIMISTIC_WRITE:
                return $fromClause . ' WITH (UPDLOCK, ROWLOCK)';

            default:
                return $fromClause;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getForUpdateSQL()
    {
        return ' ';
    }

    /**
     * {@inheritDoc}
     */
    protected function getReservedKeywordsClass()
    {
        return Keywords\SQLServerKeywords::class;
    }

    /**
     * {@inheritDoc}
     */
    public function quoteSingleIdentifier($str)
    {
        return '[' . str_replace(']', '][', $str) . ']';
    }

    /**
     * {@inheritDoc}
     */
    public function getTruncateTableSQL($tableName, $cascade = false)
    {
        $tableIdentifier = new Identifier($tableName);

        return 'TRUNCATE TABLE ' . $tableIdentifier->getQuotedName($this);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobTypeDeclarationSQL(array $field)
    {
        return 'VARBINARY(MAX)';
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultValueDeclarationSQL($field)
    {
        if (! isset($field['default'])) {
            return empty($field['notnull']) ? ' NULL' : '';
        }

        if (! isset($field['type'])) {
            return " DEFAULT '" . $field['default'] . "'";
        }

        $type = $field['type'];

        if ($type instanceof Types\PhpIntegerMappingType) {
            return ' DEFAULT ' . $field['default'];
        }

        if ($type instanceof Types\PhpDateTimeMappingType && $field['default'] === $this->getCurrentTimestampSQL()) {
            return ' DEFAULT ' . $this->getCurrentTimestampSQL();
        }

        if ($type instanceof Types\BooleanType) {
            return " DEFAULT '" . $this->convertBooleans($field['default']) . "'";
        }

        return " DEFAULT '" . $field['default'] . "'";
    }

    /**
     * {@inheritdoc}
     *
     * Modifies column declaration order as it differs in Microsoft SQL Server.
     */
    public function getColumnDeclarationSQL($name, array $field)
    {
        if (isset($field['columnDefinition'])) {
            $columnDef = $this->getCustomTypeDeclarationSQL($field);
        } else {
            $collation = isset($field['collation']) && $field['collation'] ?
                ' ' . $this->getColumnCollationDeclarationSQL($field['collation']) : '';

            $notnull = isset($field['notnull']) && $field['notnull'] ? ' NOT NULL' : '';

            $unique = isset($field['unique']) && $field['unique'] ?
                ' ' . $this->getUniqueFieldDeclarationSQL() : '';

            $check = isset($field['check']) && $field['check'] ?
                ' ' . $field['check'] : '';

            $typeDecl  = $field['type']->getSQLDeclaration($field, $this);
            $columnDef = $typeDecl . $collation . $notnull . $unique . $check;
        }

        return $name . ' ' . $columnDef;
    }

    /**
     * Returns a unique default constraint name for a table and column.
     *
     * @param string $table  Name of the table to generate the unique default constraint name for.
     * @param string $column Name of the column in the table to generate the unique default constraint name for.
     *
     * @return string
     */
    private function generateDefaultConstraintName($table, $column)
    {
        return 'DF_' . $this->generateIdentifierName($table) . '_' . $this->generateIdentifierName($column);
    }

    /**
     * Returns a hash value for a given identifier.
     *
     * @param string $identifier Identifier to generate a hash value for.
     *
     * @return string
     */
    private function generateIdentifierName($identifier)
    {
        // Always generate name for unquoted identifiers to ensure consistency.
        $identifier = new Identifier($identifier);

        return strtoupper(dechex(crc32($identifier->getName())));
    }
}
                                                                         <?php

namespace Doctrine\DBAL\Platforms\Keywords;

/**
 * DB2 Keywords.
 */
class DB2Keywords extends KeywordList
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'DB2';
    }

    /**
     * {@inheritdoc}
     */
    protected function getKeywords()
    {
        return [
            'ACTIVATE',
            'ADD',
            'AFTER',
            'ALIAS',
            'ALL',
            'ALLOCATE',
            'DOCUMENT',
            'DOUBLE',
            'DROP',
            'DSSIZE',
            'DYNAMIC',
            'EACH',
            'LOCK',
            'LOCKMAX',
            'LOCKSIZE',
            'LONG',
            'LOOP',
            'MAINTAINED',
            'ROUND_CEILING',
            'ROUND_DOWN',
            'ROUND_FLOOR',
            'ROUND_HALF_DOWN',
            'ROUND_HALF_EVEN',
            'ROUND_HALF_UP',
            'ALLOW',
            'ALTER',
            'AND',
            'ANY',
            'AS',
            'ASENSITIVE',
            'ASSOCIATE',
            'ASUTIME',
            'AT',
            'ATTRIBUTES',
            'AUDIT',
            'AUTHORIZATION',
            'AUX',
            'AUXILIARY',
            'BEFORE',
            'BEGIN',
            'BETWEEN',
            'BINARY',
            'BUFFERPOOL',
            'BY',
            'CACHE',
            'CALL',
            'CALLED',
            'CAPTURE',
            'CARDINALITY',
            'CASCADED',
            'CASE',
            'CAST',
            'CCSID',
            'CHAR',
            'CHARACTER',
            'CHECK',
            'CLONE',
            'CLOSE',
            'CLUSTER',
            'COLLECTION',
            'COLLID',
            'COLUMN',
            'COMMENT',
            'COMMIT',
            'CONCAT',
            'CONDITION',
            'CONNECT',
            'CONNECTION',
            'CONSTRAINT',
            'CONTAINS',
            'CONTINUE',
            'COUNT',
            'COUNT_BIG',
            'CREATE',
            'CROSS',
            'CURRENT',
            'CURRENT_DATE',
            'CURRENT_LC_CTYPE',
            'CURRENT_PATH',
            'CURRENT_SCHEMA',
            'CURRENT_SERVER',
            'CURRENT_TIME',
            'CURRENT_TIMESTAMP',
            'CURRENT_TIMEZONE',
            'CURRENT_USER',
            'CURSOR',
            'CYCLE',
            'DATA',
            'DATABASE',
            'DATAPARTITIONNAME',
            'DATAPARTITIONNUM',
            'EDITPROC',
            'ELSE',
            'ELSEIF',
            'ENABLE',
            'ENCODING',
            'ENCRYPTION',
            'END',
            'END-EXEC',
            'ENDING',
            'ERASE',
            'ESCAPE',
            'EVERY',
            'EXCEPT',
            'EXCEPTION',
            'EXCLUDING',
            'EXCLUSIVE',
            'EXECUTE',
            'EXISTS',
            'EXIT',
            'EXPLAIN',
            'EXTERNAL',
            'EXTRACT',
            'FENCED',
            'FETCH',
            'FIELDPROC',
            'FILE',
            'FINAL',
            'FOR',
            'FOREIGN',
            'FREE',
            'FROM',
            'FULL',
            'FUNCTION',
            'GENERAL',
            'GENERATED',
            'GET',
            'GLOBAL',
            'GO',
            'GOTO',
            'GRANT',
            'GRAPHIC',
            'GROUP',
            'HANDLER',
            'HASH',
            'HASHED_VALUE',
            'HAVING',
            'HINT',
            'HOLD',
            'HOUR',
            'HOURS',
            'IDENTITY',
            'IF',
            'IMMEDIATE',
            'IN',
            'INCLUDING',
            'INCLUSIVE',
            'INCREMENT',
            'INDEX',
            'INDICATOR',
            'INF',
            'INFINITY',
            'INHERIT',
            'INNER',
            'INOUT',
            'INSENSITIVE',
            'INSERT',
            'INTEGRITY',
            'MATERIALIZED',
            'MAXVALUE',
            'MICROSECOND',
            'MICROSECONDS',
            'MINUTE',
            'MINUTES',
            'MINVALUE',
            'MODE',
            'MODIFIES',
            'MONTH',
            'MONTHS',
            'NAN',
            'NEW',
            'NEW_TABLE',
            'NEXTVAL',
            'NO',
            'NOCACHE',
            'NOCYCLE',
            'NODENAME',
            'NODENUMBER',
            'NOMAXVALUE',
            'NOMINVALUE',
            'NONE',
            'NOORDER',
            'NORMALIZED',
            'NOT',
            'NULL',
            'NULLS',
            'NUMPARTS',
            'OBID',
            'OF',
            'OLD',
            'OLD_TABLE',
            'ON',
            'OPEN',
            'OPTIMIZATION',
            'OPTIMIZE',
            'OPTION',
            'OR',
            'ORDER',
            'OUT',
            'OUTER',
            'OVER',
            'OVERRIDING',
            'PACKAGE',
            'PADDED',
            'PAGESIZE',
            'PARAMETER',
            'PART',
            'PARTITION',
            'PARTITIONED',
            'PARTITIONING',
            'PARTITIONS',
            'PASSWORD',
            'PATH',
            'PIECESIZE',
            'PLAN',
            'POSITION',
            'PRECISION',
            'PREPARE',
            'PREVVAL',
            'PRIMARY',
            'PRIQTY',
            'PRIVILEGES',
            'PROCEDURE',
            'PROGRAM',
            'PSID',
            'ROUND_UP',
            'ROUTINE',
            'ROW',
            'ROW_NUMBER',
            'ROWNUMBER',
            'ROWS',
            'ROWSET',
            'RRN',
            'RUN',
            'SAVEPOINT',
            'SCHEMA',
            'SCRATCHPAD',
            'SCROLL',
            'SEARCH',
            'SECOND',
            'SECONDS',
            'SECQTY',
            'SECURITY',
            'SELECT',
            'SENSITIVE',
            'SEQUENCE',
            'SESSION',
            'SESSION_USER',
            'SET',
            'SIGNAL',
            'SIMPLE',
            'SNAN',
            'SOME',
            'SOURCE',
            'SPECIFIC',
            'SQL',
            'SQLID',
            'STACKED',
            'STANDARD',
            'START',
            'STARTING',
            'STATEMENT',
            'STATIC',
            'STATMENT',
            'STAY',
            'STOGROUP',
            'STORES',
            'STYLE',
            'SUBSTRING',
            'SUMMARY',
            'SYNONYM',
            'SYSFUN',
            'SYSIBM',
            'SYSPROC',
            'SYSTEM',
            'SYSTEM_USER',
            'TABLE',
            'TABLESPACE',
            'THEN',
            'TIME',
            'TIMESTAMP',
            'TO',
            'TRANSACTION',
            'TRIGGER',
            'TRIM',
            'TRUNCATE',
            'TYPE',
            'UNDO',
            'UNION',
            'UNIQUE',
            'UNTIL',
            'UPDATE',
            'DATE',
            'DAY',
            'DAYS',
            'DB2GENERAL',
            'DB2GENRL',
            'DB2SQL',
            'DBINFO',
            'DBPARTITIONNAME',
            'DBPARTITIONNUM',
            'DEALLOCATE',
            'DECLARE',
            'DEFAULT',
            'DEFAULTS',
            'DEFINITION',
            'DELETE',
            'DENSE_RANK',
            'DENSERANK',
            'DESCRIBE',
            'DESCRIPTOR',
            'DETERMINISTIC',
            'DIAGNOSTICS',
            'DISABLE',
            'DISALLOW',
            'DISCONNECT',
            'DISTINCT',
            'DO',
            'INTERSECT',
            'PUBLIC',
            'USAGE',
            'INTO',
            'QUERY',
            'USER',
            'IS',
            'QUERYNO',
            'USING',
            'ISOBID',
            'RANGE',
            'VALIDPROC',
            'ISOLATION',
            'RANK',
            'VALUE',
            'ITERATE',
            'READ',
            'VALUES',
            'JAR',
            'READS',
            'VARIABLE',
            'JAVA',
            'RECOVERY',
            'VARIANT',
            'JOIN',
            'REFERENCES',
            'VCAT',
            'KEEP',
            'REFERENCING',
            'VERSION',
            'KEY',
            'REFRESH',
            'VIEW',
            'LABEL',
            'RELEASE',
            'VOLATILE',
            'LANGUAGE',
            'RENAME',
            'VOLUMES',
            'LATERAL',
            'REPEAT',
            'WHEN',
            'LC_CTYPE',
            'RESET',
            'WHENEVER',
            'LEAVE',
            'RESIGNAL',
            'WHERE',
            'LEFT',
            'RESTART',
            'WHILE',
            'LIKE',
            'RESTRICT',
            'WITH',
            'LINKTYPE',
            'RESULT',
            'WITHOUT',
            'LOCAL',
            'RESULT_SET_LOCATOR WLM',
            'LOCALDATE',
            'RETURN',
            'WRITE',
            'LOCALE',
            'RETURNS',
            'XMLELEMENT',
            'LOCALTIME',
            'REVOKE',
            'XMLEXISTS',
            'LOCALTIMESTAMP RIGHT',
            'XMLNAMESPACES',
            'LOCATOR',
            'ROLE',
            'YEAR',
            'LOCATORS',
            'ROLLBACK',
            'YEARS',
        ];
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php

namespace Doctrine\DBAL\Platforms\Keywords;

/**
 * Drizzle Keywordlist.
 */
class DrizzleKeywords extends KeywordList
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'drizzle';
    }

    /**
     * {@inheritdoc}
     */
    protected function getKeywords()
    {
        return [
            'ABS',
            'ALL',
            'ALLOCATE',
            'ALTER',
            'AND',
            'ANY',
            'ARE',
            'ARRAY',
            'AS',
            'ASENSITIVE',
            'ASYMMETRIC',
            'AT',
            'ATOMIC',
            'AUTHORIZATION',
            'AVG',
            'BEGIN',
            'BETWEEN',
            'BIGINT',
            'BINARY',
            'BLOB',
            'BOOLEAN',
            'BOTH',
            'BY',
            'CALL',
            'CALLED',
            'CARDINALITY',
            'CASCADED',
            'CASE',
            'CAST',
            'CEIL',
            'CEILING',
            'CHAR',
            'CHARACTER',
            'CHARACTER_LENGTH',
            'CHAR_LENGTH',
            'CHECK',
            'CLOB',
            'CLOSE',
            'COALESCE',
            'COLLATE',
            'COLLECT',
            'COLUMN',
            'COMMIT',
            'CONDITION',
            'CONNECT',
            'CONSTRAINT',
            'CONVERT',
            'CORR',
            'CORRESPONDING',
            'COUNT',
            'COVAR_POP',
            'COVAR_SAMP',
            'CREATE',
            'CROSS',
            'CUBE',
            'CUME_DIST',
            'CURRENT',
            'CURRENT_DATE',
            'CURRENT_DEFAULT_TRANSFORM_GROUP',
            'CURRENT_PATH',
            'CURRENT_ROLE',
            'CURRENT_TIME',
            'CURRENT_TIMESTAMP',
            'CURRENT_TRANSFORM_GROUP_FOR_TYPE',
            'CURRENT_USER',
            'CURSOR',
            'CYCLE',
            'DATE',
            'DAY',
            'DEALLOCATE',
            'DEC',
            'DECIMAL',
            'DECLARE',
            'DEFAULT',
            'DELETE',
            'DENSE_RANK',
            'DEREF',
            'DESCRIBE',
            'DETERMINISTIC',
            'DISCONNECT',
            'DISTINCT',
            'DOUBLE',
            'DROP',
            'DYNAMIC',
            'EACH',
            'ELEMENT',
            'ELSE',
            'END',
            'ESCAPE',
            'EVERY',
            'EXCEPT',
            'EXEC',
            'EXECUTE',
            'EXISTS',
            'EXP',
            'EXTERNAL',
            'EXTRACT',
            'FALSE',
            'FETCH',
            'FILTER',
            'FLOAT',
            'FLOOR',
            'FOR',
            'FOREIGN',
            'FREE',
            'FROM',
            'FULL',
            'FUNCTION',
            'FUSION',
            'GET',
            'GLOBAL',
            'GRANT',
            'GROUP',
            'GROUPING',
            'HAVING',
            'HOLD',
            'HOUR',
            'IDENTITY',
            'IN',
            'INDICATOR',
            'INNER',
            'INOUT',
            'INSENSITIVE',
            'INSERT',
            'INT',
            'INTEGER',
            'INTERSECT',
            'INTERSECTION',
            'INTERVAL',
            'INTO',
            'IS',
            'JOIN',
            'LANGUAGE',
            'LARGE',
            'LATERAL',
            'LEADING',
            'LEFT',
            'LIKE',
            'LN',
            'LOCAL',
            'LOCALTIME',
            'LOCALTIMESTAMP',
            'LOWER',
            'MATCH',
            'MAX',
            'MEMBER',
            'MERGE',
            'METHOD',
            'MIN',
            'MINUTE',
            'MOD',
            'MODIFIES',
            'MODULE',
            'MONTH',
            'MULTISET',
            'NATIONAL',
            'NATURAL',
            'NCHAR',
            'NCLOB',
            'NEW',
            'NO',
            'NONE',
            'NORMALIZE',
            'NOT',
            'NULL_SYM',
            'NULLIF',
            'NUMERIC',
            'OCTET_LENGTH',
            'OF',
            'OLD',
            'ON',
            'ONLY',
            'OPEN',
            'OR',
            'ORDER',
            'OUT',
            'OUTER',
            'OVER',
            'OVERLAPS',
            'OVERLAY',
            'PARAMETER',
            'PARTITION',
            'PERCENTILE_CONT',
            'PERCENTILE_DISC',
            'PERCENT_RANK',
            'POSITION',
            'POWER',
            'PRECISION',
            'PREPARE',
            'PRIMARY',
            'PROCEDURE',
            'RANGE',
            'RANK',
            'READS',
            'REAL',
            'RECURSIVE',
            'REF',
            'REFERENCES',
            'REFERENCING',
            'REGR_AVGX',
            'REGR_AVGY',
            'REGR_COUNT',
            'REGR_INTERCEPT',
            'REGR_R2',
            'REGR_SLOPE',
            'REGR_SXX',
            'REGR_SXY',
            'REGR_SYY',
            'RELEASE',
            'RESULT',
            'RETURN',
            'RETURNS',
            'REVOKE',
            'RIGHT',
            'ROLLBACK',
            'ROLLUP',
            'ROW',
            'ROWS',
            'ROW_NUMBER',
            'SAVEPOINT',
            'SCOPE',
            'SCROLL',
            'SEARCH',
            'SECOND',
            'SELECT',
            'SENSITIVE',
            'SESSION_USER',
            'SET',
            'SIMILAR',
            'SMALLINT',
            'SOME',
            'SPECIFIC',
            'SPECIFICTYPE',
            'SQL',
            'SQLEXCEPTION',
            'SQLSTATE',
            'SQLWARNING',
            'SQRT',
            'START',
            'STATIC',
            'STDDEV_POP',
            'STDDEV_SAMP',
            'SUBMULTISET',
            'SUBSTRING',
            'SUM',
            'SYMMETRIC',
            'SYSTEM',
            'SYSTEM_USER',
            'TABLE',
            'TABLESAMPLE',
            'THEN',
            'TIME',
            'TIMESTAMP',
            'TIMEZONE_HOUR',
            'TIMEZONE_MINUTE',
            'TO',
            'TRAILING',
            'TRANSLATE',
            'TRANSLATION',
            'TREAT',
            'TRIGGER',
            'TRIM',
            'TRUE',
            'UESCAPE',
            'UNION',
            'UNIQUE',
            'UNKNOWN',
            'UNNEST',
            'UPDATE',
            'UPPER',
            'USER',
            'USING',
            'VALUE',
            'VALUES',
            'VARCHAR',
            'VARYING',
            'VAR_POP',
            'VAR_SAMP',
            'WHEN',
            'WHENEVER',
            'WHERE',
            'WIDTH_BUCKET',
            'WINDOW',
            'WITH',
            'WITHIN',
            'WITHOUT',
            'XML',
            'XMLAGG',
            'XMLATTRIBUTES',
            'XMLBINARY',
            'XMLCOMMENT',
            'XMLCONCAT',
            'XMLELEMENT',
            'XMLFOREST',
            'XMLNAMESPACES',
            'XMLPARSE',
            'XMLPI',
            'XMLROOT',
            'XMLSERIALIZE',
            'YEAR',
        ];
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <?php

namespace Doctrine\DBAL\Platforms\Keywords;

use function array_flip;
use function array_map;
use function strtoupper;

/**
 * Abstract interface for a SQL reserved keyword dictionary.
 */
abstract class KeywordList
{
    /** @var string[]|null */
    private $keywords = null;

    /**
     * Checks if the given word is a keyword of this dialect/vendor platform.
     *
 