<?php

namespace Doctrine\DBAL\Schema;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\DriverException;
use Doctrine\DBAL\Types\Type;
use PDOException;
use function count;
use function in_array;
use function preg_replace;
use function sprintf;
use function str_replace;
use function strpos;
use function strtok;
use function trim;

/**
 * SQL Server Schema Manager.
 */
class SQLServerSchemaManager extends AbstractSchemaManager
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

            // If we have a error code 3702, the drop database operation failed
            // because of active connections on the database.
            // To force dropping the database, we first have to close all active connections
            // on that database and issue the drop database operation again.
            if ($exception->getErrorCode() !== 3702) {
                throw $exception;
            }

            $this->closeActiveDatabaseConnections($database);

            parent::dropDatabase($database);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableSequenceDefinition($sequence)
    {
        return new Sequence($sequence['name'], (int) $sequence['increment'], (int) $sequence['start_value']);
    }

    /**
     * {@inheritdoc}
     */
    protected function _getPortableTableColumnDefinition($tableColumn)
    {
        $dbType  = strtok($tableColumn['type'], '(), ');
        $fixed   = null;
        $length  = (int) $tableColumn['length'];
        $default = $tableColumn['default'];

        if (! isset($tableColumn['name'])) {
            $tableColumn['name'] = '';
        }

        if ($default !== null) {
            while ($default !== ($default2 = preg_replace('/^\((.*)\)$/', '$1', $default))) {
                $default = trim($default2, "'");

                if ($default !== 'getdate()') {
                    continue;
                }

                $default = $this->_platform->getCurrentTimestampSQL();
            }
        }

        switch ($dbType) {
            case 'nchar':
            case 'nvarchar':
            case 'ntext':
                // Unicode data requires 2 bytes per character
                $length /= 2;
            