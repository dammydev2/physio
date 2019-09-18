<?php

namespace Doctrine\DBAL\Platforms;

use Doctrine\DBAL\Schema\Identifier;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\TableDiff;
use Doctrine\DBAL\Types\BinaryType;
use InvalidArgumentException;
use function array_merge;
use function array_unique;
use function array_values;
use function count;
use function func_get_args;
use function implode;
use function is_array;
use function is_bool;
use function is_numeric;
use function is_string;
use function sprintf;
use function trim;

/**
 * Drizzle platform
 */
class DrizzlePlatform extends AbstractPlatform
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'drizzle';
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifierQuoteCharacter()
    {
        return '`';
    }

    /**
     * {@inheritDoc}
     */
    public function getConcatExpression()
    {
        $args = func_get_args();

        return 'CONCAT(' . implode(', ', (array) $args) . ')';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDateArithmeticIntervalExpression($date, $operator, $interval, $unit)
    {
        $function = $operator === '+' ? 'DATE_ADD' : 'DATE_SUB';

        return $function . '(' . $date . ', INTERVAL ' . $interval . ' ' . $unit . ')';
    }

    /**
     * {@inheritDoc}
     */
    public function getDateDiffExpression($date1, $date2)
    {
        return 'DATEDIFF(' . $date1 . ', ' . $date2 . ')';
    }

    /**
     * {@inheritDoc}
     */
    public function getBooleanTypeDeclarationSQL(array $field)
    {
        return 'BOOLEAN';
    }

    /**
     * {@inheritDoc}
     */
    public function getIntegerTypeDeclarationSQL(array $field)
    {
        return 'INT' . $this->_getCommonIntegerTypeDeclarationSQL($field);
    }

    /**
     * {@inheritDoc}
     */
    protected function _getCommonIntegerTypeDeclarationSQL(array $columnDef)
    {
        $autoinc = '';
        if (! empty($columnDef['autoincrement'])) {
            $autoinc = ' AUTO_INCREMENT';
        }

        return $autoinc;
    }

    /**
     * {@inheritDoc}
     */
    public function getBigIntTypeDeclarationSQL(array $field)
    {
        return 'BIGINT' . $this->_getCommonIntegerTypeDeclarationSQL($field);
    }

    /**
     * {@inheritDoc}
     */
    public function getSmallIntTypeDeclarationSQL(array $field)
    {
        return 'INT' . $this->_getCommonIntegerTypeDeclarationSQL($field);
    }

    /**
     * {@inheritDoc}
     */
    protected function getVarcharTypeDeclarationSQLSnippet($length, $fixed)
    {
        return $length ? 'VARCHAR(' . $length . ')' : 'VARCHAR(255)';
    }

    /**
     * {@inheritdoc}
     */
    protected function getBinaryTypeDeclarationSQLSnippet($length, $fixed)
    {
        return 'VARBINARY(' . ($length ?: 255) . ')';
    }

    /**
     * {@inheritDoc}
     */
    protected function initializeDoctrineTypeMappings()
    {
        $this->doctrineTypeMapping = [
            'boolean'       => 'boolean',
            'varchar'       => 'string',
            'varbinary'     => 'binary',
            'integer'       => 'integer',
            'blob'          => 'blob',
            'decimal'       => 'decimal',
            'datetime'      => 'datetime',
            'date'          => 'date',
            'time'          => 'time',
            'text'          => 'text',
            'timestamp'     => 'datetime',
            'double'        => 'float',
            'bigint'        => 'bigint',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getClobTypeDeclarationSQL(array $field)
    {
        return 'TEXT';
    }

    /**
     * {@inheritDoc}
     */
    public function getBlobTypeDeclarationSQL(array $field)
    {
        return 'BLOB';
    }

    /**
     * {@inheritDoc}
     */
    public function getCreateDatabaseSQL($name)
    {
        return 'CREATE DATABASE ' . $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getDropDatabaseSQL($name)
    {
        return 'DROP 