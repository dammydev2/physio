<?php

namespace Doctrine\DBAL\Platforms;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Identifier;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Sequence;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\TableDiff;
use Doctrine\DBAL\TransactionIsolationLevel;
use Doctrine\DBAL\Types\BinaryType;
use InvalidArgumentException;
use function array_merge;
use function count;
use function explode;
use function func_get_arg;
use function func_num_args;
use function implode;
use function preg_match;
use function sprintf;
use function strlen;
use function strpos;
use function strtoupper;
use function substr;

/**
 * OraclePlatform.
 */
class OraclePlatform extends AbstractPlatform
{
    /**
     * Assertion for Oracle identifiers.
     *
     * @link http://docs.oracle.com/cd/B19306_01/server.102/b14200/sql_elements008.htm
     *
     * @param string $identifier
     *
     * @throws DBALException
     */
    public static function assertValidIdentifier($identifier)
    {
        if (! preg_match('(^(([a-zA-Z]{1}[a-zA-Z0-9_$#]{0,})|("[^"]+"))$)', $identifier)) {
            throw new DBALException('Invalid Oracle identifier');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getSubstringExpression($value, $position, $length = null)
    {
        if ($length !== null) {
          