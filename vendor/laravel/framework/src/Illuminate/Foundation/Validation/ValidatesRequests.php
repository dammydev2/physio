<?php

namespace Illuminate\Log;

use Closure;
use Throwable;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;
use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Handler\WhatFailureGroupHandler;

class LogManager implements LoggerInterface
{
    use ParsesLogConfiguration;

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The array of resolved channels.
     *
     * @var array
     */
    protected $channels = [];

    /**
     * The registered custom driver creators.
     *
     * @var array
     */
    protected $customCreators = [];

    /**
     * Create a new Log manager instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Create a new, on-demand aggregate logger instance.
     *
     * @param  array  $channels
     * @param  string|null  $channel
     * @return \Psr\Log\LoggerInterface
     */
    public function stack(array $channels, $channel = null)
    {
        return new Logger(
            $this->createStackDriver(compact('channels', 'channel')),
            $this->app['events']
        );
    }

    /**
     * Get a log channel instance.
     *
     * @param  string|null  $channel
     * @return mixed
     */
    public function channel($channel = null)
    {
        return $this->driver($channel);
    }

    /**
     * Get a log driver instance.
     *
     * @param  string|null  $driver
     * @return mixed
     */
    public function driver($driver = null)
    {
        return $this->get($driver ?? $this->getDefaultDriver());
    }

    /**
     * Attempt to get the log from the local cache.
     *
     * @param  string  $name
     * @return \Psr\Log\LoggerInterface
     */
    protected function get($name)
    {
        try {
            return $this->channels[$name] ?? with($this->resolve($name), function ($logger) use ($name) {
                return $this->channels[$name] = $thi