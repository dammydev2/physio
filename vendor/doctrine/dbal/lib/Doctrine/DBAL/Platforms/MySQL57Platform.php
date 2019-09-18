<?php

namespace Doctrine\DBAL\Platforms;

use Doctrine\DBAL\LockMode;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\ColumnDiff;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Identifier;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\TableDiff;
use Doctrine\DBAL\Types;
use InvalidArgumentException;
use function array_merge;
use function array_unique;
use function array_values;
use function count;
use function crc32;
use function dechex;
use function explode;
use function func_get_args;
use function implode;
use function is_array;
use function is_bool;
use function is_numeric;
use function is_string;
use function preg_match;
use function preg_replace;
use function sprintf;
use function str_replace;
use function stripos;
use function stristr;
use function strlen;
use function strpos;
use function strtoupper;
use function substr;
use function substr_count;

/**
 * The SQLServerPlatform provides the behavior, features and SQL dialect of the
 * Microsoft SQL Server database platform.
 */
class SQLServerPlatform extends AbstractPlatform
{
    /**
     * {@inheritdoc}
     */
    public function getCurrentDateSQL()
    {
        return $this->getConvertExpression('date', 'GETDATE()');
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentTimeSQL()
    {
        return $this->getConvertExpression('time', 'GETDATE()');
    }

    /**
     * R