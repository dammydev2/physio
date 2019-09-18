<?php

namespace Illuminate\Redis\Limiters;

use Illuminate\Support\InteractsWithTime;
use Illuminate\Contracts\Redis\LimiterTimeoutException;

class ConcurrencyLimiterBuilder
{
    use InteractsWithTime;

    /**
     * The Redis connection.
     *
     * @var \Illuminate\Redis\Connections\Connection
     */
    public $connection;

    /**
     * The name of the lock.
     *
     * @var string
     */
    public $name;

    /**
     * The maximum number of entities that can hold the lock at the same time.
     *
     * @var int
     */
    public $maxLocks;

    /**
     * The number of seconds to maintain the lock until it is automatically released.
     *
     * @var int
     */
    public $releaseAfter = 60;

    /**
     * The amount of time to block until a lock is available.
     *
     * @var int
     */
    public $timeout = 3;

    /**
     * Create a new builder instance.
     *
     * @param  \Illuminate\Redis\Connections\Connection  $connection
     * @param  string  $name
     * @return void
     */
    public function __construct($connection, $name)
    {
        $this->name = $name;
        $this->connection = $connection;
    }

    /**
     * Set the maximum number of locks that can obtained per time window.
     *
     * @param  int  $maxLocks
     * @return $this
     */
    public function limit($maxLocks)
    {
        $this->maxLocks = $maxLocks;

        return $this;
    }

    /**
     * Set the number of seconds