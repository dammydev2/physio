<?php

namespace Doctrine\DBAL\Schema;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\DriverException;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Types\Type;
use const CASE_LOWER;
use function array_change_key_case;
use function array_values;
use function assert;
use function preg_match;
use function sprintf;
use function strpos;
use function strtolower;
use function strtoupper;
use function trim;

/**
 * Oracle Schema Manager.
 */
class OracleSchemaManager extends AbstractSchemaManager
{
    /**
     * {@inheritdoc}
     */
    public function dropDatabase($database)
    {
        try {
            parent::dropDatabase($database);
        } catch (DBALException $exception) {
            $exception = $exception->getPrevious();

            if (! $exception instanceof DriverException) {
                throw $exception;
            }

            // If we have a error code 1940 (ORA-01940), the drop database operation failed
            // because of active connections on the database.
            // To force dropping the database, we first have to close all active connections
            // on that database and issue the drop database operation again.
            if ($exception->getErrorCode() !== 1940) {
                throw $exception;
            }

            $this->killUserSessions($database);

            parent::dropDatabase($database);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableViewDefinition($view)
    {
        $view = array_change_key_case($view, CASE_LOWER);

        return new View($this->getQuotedIdentifierName($view['view_name']), $view['text']);
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableUserDefinition($user)
    {
        $user = array_change_key_case($user, CASE_LOWER);

        return [
            'user' => $user['username'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableTableDefinition($table)
    {
        $table = array_change_key_case($table, CASE_LOWER);

        return $this->getQuotedIdentifierName($table['table_name']);
    }

    /**
     * {@inheritdoc}
     *
     * @link http://ezcomponents.org/docs/api/trunk/DatabaseSchema/ezcDbSchemaPgsqlReader.html
     */
    protected function _getPortableTableIndexesList($tableIndexes, $tableName = null)
    {
        $indexBuffer = [];
        foreach ($tableIndexes as $tableIndex) {
            $tableIndex = array_change_key_case($tableIndex, CASE_LOWER);

            $keyName = strtolower($tableIndex['name']);
            $buffer  = [];

            if (strtolower($tableIndex['is_primary']) === 'p') {
                $keyName              = 'primary';
                $buffer['primary']    = true;
                $buffer['non_unique'] = false;
            } else {
                $buffer['primary']    = false;
                $buffer['non_unique'] = ! $tableIndex['is_unique'];
            }
            $buffer['key_name']    = $keyName;
            $buffer['column_name'] = $this->getQuotedIdentifierName($tableIndex['column_name']);
            $indexBuffer[]         = $buffer;
        }

        return parent::_getPortableTableIndexesList($indexBuffer, $tableName);
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableTableColumnDefinition($tableColumn)
    {
        $tableColumn = array_change_key_case($tableColumn, CASE_LOWER);

        $dbType = strtolower($tableColumn['data_type']);
        if (strpos($dbType, 'timestamp(') === 0) {
            if (strpos($dbType, 'with time zone')) {
                $dbType = 'timestamptz';
            } else {
                $dbType = 'timestamp';
            }
        }

        $unsigned = $fixed = $precision = $scale = $length = null;

        if (! isset($tableColumn['column_name'])) {
            $tableColumn['column_name'] = '';
        }

        // Default values returned from database sometimes have trailing spaces.
        $tableColumn['data_default'] = trim($tableColumn['data_default']);

        if ($tableColumn['data_default'] === '' || $tableColumn['data_default'] === 'NULL') {
            $tableColumn['data_default'] = null;
        }

        if ($tableColumn['data_default'] !== null) {
            // Default values returned from database are enclosed in single quotes.
            $tableColumn['data_default'] = trim($tableColumn['data_default'], "'");
        }

        if ($tableColumn['data_precision'] !== null) {
            $precision = (int) $tableColumn['data_precision'];
        }

        if ($tableColumn['data_scale'] !== null) {
            $scale = (int) $tableColumn['data_scale'];
        }

        $type                    = $this->_platform->getDoctrineTypeMapping($dbType);
        $type                    = $this->extractDoctrineTypeFromComment($tableColumn['comments'], $type);
        $tableColumn['comments'] = $this->removeDoctrineTypeFromComment($tableColumn['comments'], $type);

        switch ($dbType) {
            case 'number':
                if ($precision === 20 && $scale === 0) {
                    $type = 'bigint';
                } elseif ($precision === 5 && $scale === 0) {
                    $type = 'smallint';
                } elseif ($precision === 1 && $scale === 0) {
                    $type = 'boolean';
                } elseif ($scale > 0) {
                    $type = 'decimal';
                }

                break;
            case 'varchar':
            case 'varchar2':
            case 'nvarchar2':
                $length = $tableColumn['char_length'];
                $fixed  = false;
                break;
            case 'char':
            ca