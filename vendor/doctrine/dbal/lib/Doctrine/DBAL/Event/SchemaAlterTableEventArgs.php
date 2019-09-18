<?php

namespace Doctrine\DBAL\Platforms;

use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Identifier;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\TableDiff;
use Doctrine\DBAL\TransactionIsolationLevel;
use Doctrine\DBAL\Types\BlobType;
use Doctrine\DBAL\Types\TextType;
use InvalidArgumentException;
use function array_diff_key;
use function array_merge;
use function array_unique;
use function array_values;
use function count;
use function func_get_args;
use function implode;
use function in_array;
use function is_numeric;
use function is_string;
use function sprintf;
use function str_replace;
use function strtoupper;
use function trim;

/**
 * The MySqlPlatform provides the behavior, features and SQL dialect of the
 * MySQL database platform. This platform represents a MySQL 5.0 or greater platform that
 * uses the InnoDB storage engine.
 *
 * @todo   Rename: MySQLPlatform
 */
class MySqlPlatform extends AbstractPlatform
{
    public const LENGTH_LIMIT_TINYTEXT   = 255;
    public const LENGTH_LIMIT_TEXT       = 65535;
    public const LENGTH_LIMIT_MEDIUMTEXT = 16777215;

    public const LENGTH_LIMIT_TINYBLOB   = 255;
    public const LENGTH_LIMIT_BLOB       = 65535;
    public const LENGTH_LIMIT_MEDIUMBLOB = 16777215;

    /**
     * {@inheritDoc}
     */
    protected function doModi