<?php

namespace Doctrine\DBAL\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\AbstractOracleDriver\EasyConnectString;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Schema\OracleSchemaManager;

/**
 * Abstract base implementation of the {@link Doctrine\DBAL\Driver} interface for Oracle based drivers.
 */
abstract class AbstractOracleDriver implements Driver, ExceptionConverterDriver
{
    /**
     * {@inheritdoc}
     */
    public function convertException($message, DriverException $exception)
    {
        switch ($exception->getErrorCode()) {
            case '1':
            case '2299':
            case '38911':
                return new Exception\UniqueConstraintViolationException($message, $exception);

            case '904':
                return new Exception\InvalidFieldNameException($message, $exception);

            case '918':
            case '960':
                return new Exception\NonUniqueFieldNameException($message, $exception);

            case '923':
                return new Exception\SyntaxErrorException($message, $exception);

            case '942':
                return new Exception\TableNotFoundExcepti