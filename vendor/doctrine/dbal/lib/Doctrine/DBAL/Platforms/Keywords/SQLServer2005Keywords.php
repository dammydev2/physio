<?php

namespace Doctrine\DBAL\Schema;

use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Platforms\PostgreSqlPlatform;
use Doctrine\DBAL\Types\Type;
use const CASE_LOWER;
use function array_change_key_case;
use function array_filter;
use function array_keys;
use function array_map;
use function array_shift;
use function assert;
use function explode;
use function implode;
use function in_array;
use function preg_match;
use function preg_replace;
use function sprintf;
use function str_replace;
use function stripos;
use function strlen;
use function strpos;
use function strtolower;
use function trim;

/**
 * PostgreSQL Schema Manager.
 */
class PostgreSqlSchemaManager extends AbstractSchemaManager
{
    /** @var string[] */
    private $