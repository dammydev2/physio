<?php

namespace Doctrine\DBAL\Schema;

use Doctrine\DBAL\Platforms\MariaDb1027Platform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\Type;
use const CASE_LOWER;
use function array_change_key_case;
use function array_shift;
use function array_values;
use function end;
use function explode;
use function preg_match;
use function preg_replace;
use function str_replace;
use function stripslashes;
use function strpos;
use function strtok;
use function strtolower;

/**
 * Schema manager for the MySql RDBMS.
 */
class MySqlSchemaManager extends AbstractSchemaManager
{
    /**
     * {@inheritdoc}
     */
    protected function _getPortableViewDefinition($view)
    {
        return new View($view['TABLE_NAME'