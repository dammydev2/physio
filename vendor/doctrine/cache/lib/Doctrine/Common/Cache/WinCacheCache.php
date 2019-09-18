<?php

namespace Doctrine\DBAL\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\MariaDb1027Platform;
use Doctrine\DBAL\Platforms\MySQL57Platform;
use Doctrine\DBAL\Platforms\MySQL80Platform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Schema\MySqlSchemaManager;
use Doctrine\DBAL\VersionAwarePlatformDriver;
use function preg_match;
use function stripos;
use function version_compare;

/**
 * Abstract base implementation of the {@link Doctrine\DBAL\Driver} interface for MySQL based drivers.
 */
abstract class AbstractMySQLDriver implements Driver, ExceptionConverterDriver, VersionAwarePlatformDriver
{
    /**
     * {@inheritdoc}
     *
     * @link http://dev.mysql.com/doc/refman/5.7/en/error-messages-client.html
     * @link http://dev.mysql.com/doc/refman/5.7/en/error-messages-server.html
     */
    public function convertException($message, DriverException $exception)
    {
        switch ($exception->getErrorCode()) {
            case '1213':
                return new Exception\DeadlockException($message, $exception);
            case '1205':
                return new Exception\LockWaitTimeoutException($message, $exception);
            case '1050':
                return new Exception\TableExistsException($message, $exception);

            case '1051':
            case '1146':
                return new Exception\TableNotFoundException($message, $exception);

            case '1216':
            case '1217':
            case '1451':
            case '1452':
            case '1701':
                return new Exception\ForeignKeyConstraintViolationException($message, $exception);

            case '1062':
            case '1557':
            case '1569':
            case '1586':
                return new Exception\UniqueConstraintViolationException($message, $exception);

            case '1054':
            case '1166':
            case '1611':
                return new Exception\InvalidFieldNameException($message, $exception);

            case '1052':
            case '1060':
            case '1110':
                return new Exception\NonUniqueFieldNameException($message, $exception);

            case '1064':
          