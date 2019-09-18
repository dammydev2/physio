<?php

namespace Illuminate\Database;

use PDO;
use Closure;
use Exception;
use PDOStatement;
use LogicException;
use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Database\Query\Expression;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Events\QueryExecuted;
use Doctrine\DBAL\Connection as DoctrineConnection;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Schema\Builder as SchemaBuilder;
use Illuminate\Database\Query\Grammars\Grammar as QueryGrammar;

class Connection implements ConnectionInterface
{
    use DetectsDeadlocks,
        DetectsLostConnections,
        Concerns\ManagesTransactions;

    /**
     * The active PDO connection.
     *
     * @var \PDO|\Closure
     */
    protected $pdo;

    /**
     * The active PDO connection used for reads.
     *
     * @var \PDO|\Closure
     */
    protected $readPdo;

    /**
     * The name of the connected database.
     *
     * @var string
     */
    protected $database;

    /**
    