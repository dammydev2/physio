<?php

namespace Doctrine\DBAL;

use Closure;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Cache\ArrayStatement;
use Doctrine\DBAL\Cache\CacheException;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Cache\ResultCacheStatement;
use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\DBAL\Driver\PingableConnection;
use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Driver\ServerInfoAwareConnection;
use Doctrine\DBAL\Driver\Statement as DriverStatement;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Types\Type;
use Exception;
use Throwable;
use function array_key_exists;
use function array_merge;
use function assert;
use function func_get_args;
use function implode;
use function is_int;
use function is_string;
use function key;

/**
 * A wrapper around a Doctrine\DBAL\Driver\Connection that adds features like
 * events, transaction isolation levels, configuration, emulated transaction nesting,
 * lazy connecting and more.
 */
class Connection implements DriverConnection
{
    /**
     * Constant for transaction isolation level READ UNCOMMITTED.
     *
     * @deprecated Use TransactionIsolationLevel::READ_UNCOMMITTED.
     */
    public const TRANSACTION_READ_UNCOMMI