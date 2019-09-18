<?php

namespace Doctrine\DBAL\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use function array_key_exists;
use function array_keys;
use function array_unshift;
use function func_get_args;
use function func_num_args;
use function implode;
use function is_array;
use function is_object;
use function key;
use function strtoupper;
use function substr;

/**
 * QueryBuilder class is responsible to dynamically create SQL queries.
 *
 * Important: Verify that every feature you use will work with your database vendor.
 * SQL Query Builder does not attempt to validate the generated SQL at all.
 *
 * The query builder does no validation whatsoever if certain features even work with the
 * underlying database vendor. Limit queries and joins are NOT applied