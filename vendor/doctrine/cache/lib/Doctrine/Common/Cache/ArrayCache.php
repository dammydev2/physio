<?php

namespace Doctrine\DBAL;

use Doctrine\DBAL\Driver\DriverException as DriverExceptionInterface;
use Doctrine\DBAL\Driver\ExceptionConverterDriver;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Exception;
use Throwable;
use function array_map;
use function bin2hex;
use function get_class;
use function gettype;
use function implode;
use function is_object;
use function is_resource;
use function is_string;
use function json_encode;
use function sprintf;
use function str_split;

class DBALException extends Exception
{
    /**
     * @param string $method
     *
     * @return \Doctrine\DBAL\DBALException
     */
    public static function notSupported($method)
    {
        return new self(sprintf("Operation '%s' is not supported by platform.", $method));
    }

    public static function invalidPlatformSpecified() : self
    {
        return new self(
            "Invalid 'platform' option specified, need to give an instance of " . AbstractPlatform::class . '.'
        );
    }

    /**
     * @param mixed $invalidPlatform
     */
    public static function invalidPlatformType($invalidPlatform) : self
    {
        if (is_object($invalidPlatform)) {
            return new self(
                sprintf(
                    "Option 'platform' must be a subtype of '%s', instance of '%s' given",
                    AbstractPlatform::class,
                    get_class($invalidPlatform)
                )
            );
        }

        return new self(
            sprintf(
                "Option 'platform' must be an object and subtype of '%s'. Got '%s'",
                AbstractPlatform::class,
                gettype($invalidPlatform)
            )
        );
    }

    /**
     * Returns a new instance for an invalid specified platform version.
     *
     * @param string $version        The invalid platform version given.
     * @param string $expectedFormat The expected platform version format.
     *
     * @return DBALException
     */
    public static function invalidPlatformVersio