<?php

namespace Illuminate\Cache;

use Illuminate\Contracts\Cache\LockProvider;
use Illuminate\Contracts\Redis\Factory as Redis;

class RedisStore extends TaggableStore implements LockProvider
{
    /**
     * The Redis factory implementation.
     *
     * @var \Illuminate\Contracts\Redis\Factory
     */
    protected $redis;

    /**
     * A string that should be prepended to keys.
     *
     * @var string
     */
    protected $prefix;

    /**
     * The Redis connection that should be used.
     *
     * @var string
     */
    protected $connection;

    /**
     * Create a new Redis store.
     *
     * @param  \Illuminate\Contracts\Redis\Factory  $redis
     * @param  string  $prefix
     * @param  string  $connection
     * @return void
     */
    public function __construct(Redis $redis, $prefix = '', $connection = 'default')
    {
        $this->redis = $redis;
        $this->setPrefix($prefix);
        $this->setConnection($connection);
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string|array  $key
     * @return mixed
     */
    public function get($key)
    {
        $value = $this->connection()->get($this->prefix.$key);

        return ! is_null($value) ? $this->unserialize($value) : null;
    }

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     *
     * @param  array  $keys
     * @return array
     */
    public function many(array $keys)
    {
        $results = [];

        $values = $this->connection()->mget(array_map(function ($key) {
            return $this->prefix.$key;
        }, $keys));

        foreach ($values as $index => $value) {
            $results[$keys[$index]] = ! is_null($value) ? $this->unserialize($value) : null;
        }

        return $results;
    }

    /**
     * Store an item in the cache for a given number of seconds.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  int  $seconds
     * @return bool
     */
    public function put($key, $value, $seconds)
    {
        return (bool) $this->connection()->sete