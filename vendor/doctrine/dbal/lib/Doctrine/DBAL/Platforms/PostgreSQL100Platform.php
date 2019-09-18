<?php

namespace Doctrine\DBAL\Platforms\Keywords;

/**
 * MariaDb reserved keywords list.
 *
 * @link https://mariadb.com/kb/en/the-mariadb-library/reserved-words/
 */
final class MariaDb102Keywords extends MySQLKeywords
{
    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return 'MariaDb102';
    }

    /**
     * {@inheritdoc}
     */
    protected function getKeywords() : array
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
  