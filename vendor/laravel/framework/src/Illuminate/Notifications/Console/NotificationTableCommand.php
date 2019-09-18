<?php

namespace Illuminate\Queue;

use Illuminate\Support\Str;
use Illuminate\Queue\Jobs\RedisJob;
use Illuminate\Contracts\Redis\Factory as Redis;
use Illuminate\Contracts\Queue\Queue as QueueContract;

class RedisQueue extends Queue implements QueueContract
{
    /**
     * The Redis factory implementation.
     *
     * @var \Illuminate\Contracts\Redis\Factory
     */
    protected $redis;

    /**
     * The connection name.
     *
     * @var string
     */
    protected $connection;

    /**
     * The name of the default queue.
     *
     * @var string
     */
    protected $default;

    /**
     * The expiration time of a job.
     *
     * @var int|null
     */
    protected $retryAfter = 60;

    /**
     * The maximum number of seconds to block for a job.
     *
     * @var int|null
     */
    protected $blockFor = null;

    /**
     * Create a new Redis queue instance.
     *
     * @param  \Illuminate\Contracts\Redis\Factory  $redis
     * @param  string  $default
     * @param  string  $connection
     * @param  int  $retryAfter
     * @param  int|null  $blockFor
     * @return void
     */
    public function __construct(Redis $redis, $default = 'default', $connection = null, $retryAfter = 60, $blockFor = null)
    {
        $this->redis = $redis;
        $this->default = $default;
        $this->blockFor = $blockFor;
        $this->connection = $connection;
        $this->retryAfter = $retryAfter;
    }

    /**
     * Get the size of the queue.
     *
     * @param  string  $queue
     * @return int
     */
    public function size($queue = null)
    {
        $queue = $this->getQueue($queue);

        return $this->getConnection()->eval(
            LuaScripts::size(), 3, $queue, $queue.':delayed', $queue.':reserved'
        );
    }

    /**
     * Push