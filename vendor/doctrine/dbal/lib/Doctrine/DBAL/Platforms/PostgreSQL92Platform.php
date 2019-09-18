<?php

namespace Doctrine\DBAL\Platforms\Keywords;

/**
 * MySQL 5.7 reserved keywords list.
 */
class MySQL57Keywords extends MySQLKeywords
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'MySQL57';
    }

    /**
     * {@inheritdoc}
     *
     * @link http://dev.mysql.com/doc/mysqld-version-reference/en/mysqld-version-reference-reservedwords-5-7.html
     */
    protected function getKeywords()
    {
        return [
            'ACCESSIBLE',
            'ADD',
            'ALL',
            'ALTER',
            'ANALYZE',
            'AND',
            'AS',
            'ASC',
            'ASENSITIVE',
            'BEFORE',
            'BETWEEN',
            'BIGINT',
            'BINARY',
            'BLOB',
            'BOTH',
            'BY',
            'CALL',
            'CASCADE',
            'CASE',
            'CHANGE',
            'CHAR',
            'CHARACTER',
            'CHECK',
            'COLLATE',
            'COLUMN',
            'CONDITION',
            'CONSTRAINT',
            'CONTINUE',
            'CONVERT',
            'CREATE',
            'CROSS',
            'CURRENT_DATE',
            'CURRENT_TIME',
            'CURRENT_TIMESTAMP',
            'CURRENT_USER',
            'CURSOR',
            'DATABASE',
            'DATABASES',
            'DAY_HOUR',
            'DAY_MICROSECOND',
            'DAY_MINUTE',
            'DAY