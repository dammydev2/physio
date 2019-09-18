<?php

namespace Illuminate\Database\Query;

use Closure;
use RuntimeException;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Builder
{
    use BuildsQueries, ForwardsCalls, Macroable {
        __call as macroCall;
    }

    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\ConnectionInterface
     */
    public $connection;

    /**
     * The database query grammar instance.
     *
     * @var \Illuminate\Database\Query\Grammars\Grammar
     */
    public $grammar;

    /**
     * The database query post processor instance.
     *
     * @var \Illuminate\Database\Query\Processors\Processor
     */
    public $processor;

    /**
     * The current query value bindings.
     *
     * @var array
     */
    public $bindings = [
        'select' => [],
        'from'   => [],
        'join'   => [],
        'where'  => [],
        'having' => [],
        'order'  => [],
        'union'  => [],
    ];

    /**
     * An aggregate function and column to be run.
     *
     * @var array
     */
    public $aggregate;

    /**
     * The columns that should be returned.
     *
     * @var array
     */
    public $columns;

    /**
     * Indicates if the query returns distinct results.
     *
     * @var bool
     */
    public $distinct = false;

    /**
     * The table which the query is targeting.
     *
     * @var string
     */
    public $from;

    /**
     * The table joins for the query.
     *
     * @var array
     */
    public $joins;

    /**
     * The where constraints for the query.
     *
     * @var array
     */
    public $wheres = [];

    /**
     * The groupings for the query.
     *
     * @var array
     */
    public $groups;

    /**
     * The having constraints for the query.
     *
     * @var array
     */
    public $havings;

    /**
     * The orderings for the query.
     *
     * @var array
     */
    public $orders;

    /**
     * The maximum number of records to return.
     *
     * @var int
     */
    public $limit;

    /**
     * The number of records to skip.
     *
     * @var int
     */
    public $offset;

    /**
     * The query union statements.
     *
     * @var array
     */
    public $unions;

    /**
     * The maximum number of union records to return.
     *
     * @var int
     */
    public $unionLimit;

    /**
     * The number of union records to skip.
     *
     * @var int
     */
    public $unionOffset;

    /**
     * The orderings for the union query.
     *
     * @var array
     */
    public $unionOrders;

    /**
     * Indicates whether row locking is being used.
     *
     * @var string|bool
     */
    public $lock;

    /**
     * All of the available clause operators.
     *
     * @var array
     */
    public $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>',
        'rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
    ];

    /**
     * Whether use write pdo for select.
     *
     * @var bool
     */
    public $useWritePdo = false;

    /**
     * Create a new query builder instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface  $connection
     * @param  \Illuminate\Database\Query\Grammars\Grammar|null  $grammar
     * @param  \Illuminate\Database\Query\Processors\Processor|null  $processor
     * @return void
     */
    public function __construct(ConnectionInterface $connection,
                                Grammar $grammar = null,
                                Processor $processor = null)
    {
        $this->connection = $connection;
        $this->grammar = $grammar ?: $connection->getQueryGrammar();
        $this->processor = $processor ?: $connection->getPostProcessor();
    }

    /**
     * Set the columns to be selected.
     *
     * @param  array|mixed  $columns
     * @return $this
     */
    public function select($columns = ['*'])
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();

        return $this;
    }

    /**
     * Add a subselect expression to the query.
     *
     * @param  \Closure|\Illuminate\Database\Query\Builder|string $query
     * @param  string  $as
     * @return \Illuminate\Database\Query\Builder|static
     *
     * @throws \InvalidArgumentException
     */
    public function selectSub($query, $as)
    {
        [$query, $bindings] = $this->createSub($query);

        return $this->selectRaw(
            '('.$query.') as '.$this->grammar->wrap($as), $bindings
        );
    }

    /**
     * Add a new "raw" select expression to the query.
     *
     * @param  string  $expression
     * @param  array   $bindings
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function selectRaw($expression, array $bindings = [])
    {
        $this->addSelect(new Expression($expression));

        if ($bindings) {
            $this->addBinding($bindings, 'select');
        }

        return $this;
    }

    /**
     * Makes "from" fetch from a subquery.
     *
     * @param  \Closure|\Illuminate\Database\Query\Builder|string $query
     * @param  string  $as
     * @return \Illuminate\Database\Query\Builder|static
     *
     * @throws \InvalidArgumentException
     */
    public function fromSub($query, $as)
    {
        [$query, $bindings] = $this->createSub($query);

        return $this->fromRaw('('.$query.') as '.$this->grammar->wrap($as), $bindings);
    }

    /**
     * Add a raw from clause to the query.
     *
     * @param  string  $expression
     * @param  mixed   $bindings
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function fromRaw($expression, $bindings = [])
    {
        $this->from = new Expression($expression);

        $this->addBinding($bindings, 'from');

        return $this;
    }

    /**
     * Creates a subquery and parse it.
     *
     * @param  \Closure|\Illuminate\Database\Query\Builder|string $query
     * @return array
     */
    protected function createSub($query)
    {
        // If the given query is a Closure, we will execute it while passing in a new
        // query instance to the Closure. This will give the developer a chance to
        // format and work with the query before we cast it to a raw SQL string.
        if ($query instanceof Closure) {
            $callback = $query;

            $callback($query = $this->forSubQuery());
        }

        return $this->parseSub($query);
    }

    /**
     * Parse the subquery into SQL and bindings.
     *
     * @param  mixed  $query
     * @return array
     */
    protected function parseSub($query)
    {
        if ($query instanceof self || $query instanceof EloquentBuilder) {
            return [$query->toSql(), $query->getBindings()];
        } elseif (is_string($query)) {
            return [$query, []];
        } else {
            throw new InvalidArgumentException;
        }
    }

    /**
     * Add a new select column to the query.
     *
     * @param  array|mixed  $column
     * @return $this
     */
    public function addSelect($column)
    {
        $column = is_array($column) ? $column : func_get_args();

        $this->columns = array_merge((array) $this->columns, $column);

        return $this;
    }

    /**
     * Force the query to only return distinct results.
     *
     * @return $this
     */
    public function distinct()
    {
        $this->distinct = true;

        return $this;
    }

    /**
     * Set the table which the query is targeting.
     *
     * @param  string  $table
     * @return $this
     */
    public function from($table)
    {
        $this->from = $table;

        return $this;
    }

    /**
     * Add a join clause to the query.
     *
     * @param  string  $table
     * @param  \Closure|string  $first
     * @param  string|null  $operator
     * @param  string|null  $second
     * @param  string  $type
     * @param  bool    $where
     * @return $this
     */
    public function join($table, $first, $operator = null, $second = null, $type = 'inner', $where = false)
    {
        $join = $this->newJoinClause($this, $type, $table);

        // If the first "column" of the join is really a Closure instance the developer
        // is trying to build a join with a complex "on" clause containing more than
        // one condition, so we'll add the join and call a Closure with the query.
        if ($first instanceof Closure) {
            call_user_func($first, $join);

            $this->joins[] = $join;

            $this->addBinding($join->getBindings(), 'join');
        }

        // If the column is simply a string, we can assume the join simply has a basic
        // "on" clause with a single condition. So we will just build the join with
        // this simple join clauses attached to it. There is not a join callback.
        else {
            $method = $where ? 'where' : 'on';

            $this->joins[] = $join->$method($first, $operator, $second);

            $this->addBinding($join->getBindings(), 'join');
        }

        return $this;
    }

    /**
     * Add a "join where" clause to the query.
     *
     * @param  string  $table
     * @param  \Closure|string  $first
     * @param  string  $operator
     * @param  string  $second
     * @param  string  $type
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function joinWhere($table, $first, $operator, $second, $type = 'inner')
    {
        return $this->join($table, $first, $operator, $second, $type, true);
    }

    /**
     * Add a subquery join clause to the query.
     *
     * @param  \Closure|\Illuminate\Database\Query\Builder|string $query
     * @param  string  $as
     * @param  \Closure|string  $first
     * @param  string|null  $operator
     * @param  string|null  $second
     * @param  string  $type
     * @param  bool    $where
     * @return \Illuminate\Database\Query\Builder|static
     *
     * @throws \InvalidArgumentException
     */
    public function joinSub($query, $as, $first, $operator = null, $second = null, $type = 'inner', $where = false)
    {
        [$query, $bindings] = $this->createSub($query);

        $expression = '('.$query.') as '.$this->grammar->wrap($as);

        $this->addBinding($bindings, 'join');

        return $this->join(new Expression($expression), $first, $operator, $second, $type, $where);
    }

    /**
     * Add a left join to the query.
     *
     * @param  string  $table
     * @param  \Closure|string  $first
     * @param  string|null  $operator
     * @param  string|null  $second
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function leftJoin($table, $first, $operator = null, $second = null)
    {
        return $this->join($table, $first, $operator, $second, 'left');
    }

    /**
     * Add a "join where" clause to the query.
     *
     * @param  string  $table
     * @param  \Closure|string  $first
     * @param  string  $operator
     * @param  string  $second
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function leftJoinWhere($tab