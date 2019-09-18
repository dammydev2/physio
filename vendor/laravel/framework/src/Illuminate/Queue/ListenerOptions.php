<?php

namespace Illuminate\Redis;

use InvalidArgumentException;
use Illuminate\Contracts\Redis\Factory;
use Illuminate\Redis\Connections\Connection;

/**
 * @mixin \Illuminate\Redis\Connections\Connection
 */
class RedisManager implements Factory
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The name of the default driver.
     *
     * @var string
     */
    protected $driver;

    /**
     * The Redis server configurations.
     *
     * @var array
     */
    protected $config;

    /**
     * The Redis connections.
     *
     * @var mixed
     */
    protected $connections;

    /**
     * Indicates whether event dispatcher is set on connections.
     