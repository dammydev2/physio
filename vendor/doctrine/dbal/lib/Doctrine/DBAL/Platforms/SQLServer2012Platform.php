<?php

namespace Doctrine\DBAL\Query\Expression;

use Doctrine\DBAL\Connection;
use function func_get_arg;
use function func_get_args;
use function func_num_args;
use function implode;
use function sprintf;

/**
 * ExpressionBuilder class is responsible to dynamically create SQL query parts.
 */
class ExpressionBuilder
{
    public const EQ  = '=';
    public const NEQ = '<>';
    public const LT  = '<';
    public const LTE = '<=';
    public const GT  = '>';
    public const GTE = '>=';

    /**
     * The DBAL Connection.
     *
     * @var Connection
     */
    private $connection;

    /**
     * Initializes a new <tt>ExpressionBuilder</tt>.
     *
     * @param Connection $connection The DBAL Connection.
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Creates a conjunction of the given boolean expressions.
     *
     * Example:
     *
     *     [php]
     *     // (u.type = ?) AND (u.role = ?)
     *     $expr->andX('u.type = ?', 'u.role = ?'));
     *
     * @param mixed $x Optional clause. Defaults = null, but requires
     *                 at least one defined when converting to string.
     *
     * @return CompositeExpression
     */
    public function andX($x = null)
    {
        return new CompositeExpression(CompositeExpression::TYPE_AND, func_get_args());
    }

    /**
     * Creates a disjunction of the given boolean expressions.
     *
     * Example:
     *
     *     [php]
     *     // (u.type = ?) OR (u.role = ?)
     *     $qb->where($qb->expr()->orX('u.type = ?', 'u.role = ?'));
     *
     * @param mixed $x Optional clause. Defaults = null, but requires
     *                 at least one defined when converting to string.
     *
     * @return CompositeExpression
     */
    public function orX($x = null)
    {
        return new CompositeExpression(CompositeExpression::TYPE_OR, func_get_args());
    }

    /**
     * Creates a comparison expression.
     *
     * @param mixed  $x        The left expression.
     * @param string $operator One of the ExpressionBuilder::* constants.
     * @param mixed  $y        The right expression.
     *
     * @return string
     */
    public function comparison($x, $operator, $y)
    {
        return $x . ' ' . $operator . ' ' . $y;
    }

    /**
     * Creates an equality comparison expression with the given arguments.
     *
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> = <right expr>. Example:
     *
     *     [php]
     *     // u.id = ?
     *     $expr->eq('u.id', '?');
     *
     * @param mixed $x The left expression.
     * @param mixed $y The right expression.
     *
     * @return string
     */
    public function eq($x, $y)
    {
        return $this->comparison($x, self::EQ, $y);
    }

    /**
     * Creates a non equality comparison expression with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> <> <right expr>. Example:
     *
     *     [php]
     *     // u.id <> 1
     *     $q->where($q->expr()->neq('u.id', '1'));
     *
     * @param mixed $x The left expression.
     * @param mixed $y The right expression.
     *
     * @return string
     */
    public function neq($x, $y)
    {
        return $this->comparison($x, self::NEQ, $y);
    }

    /**
     * Creates a lower-than comparison expression with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> < <right expr>. Example:
     *
     *     [php]
     *     // u.id < ?
     *     $q->where($q->expr()->lt('u.id', '?'));
     *
     * @param mixed $x The left expression.
     * @param mixed $y The right expression.
     *
     * @return string
     */
    public function lt($x, $y)
    {
        return $this->comparison($x, self::LT, $y);
    }

    /**
     * Creates a lower-than-equal comparison expression with the given arguments.
     * First argument is considered the left expression and the second is the right expression.
     * When converted to string, it will generated a <left expr> <= <right expr>. Example:
     *
     *     [php]
     *     // u.id <= ?
     *     $q->where($q->expr()->lte('u.id', '?'));
     *
     * @param mixed $x The left expr