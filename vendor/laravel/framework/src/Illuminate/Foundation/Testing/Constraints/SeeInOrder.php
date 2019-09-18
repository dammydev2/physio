<?php

namespace Illuminate\Log;

use Closure;
use RuntimeException;
use Psr\Log\LoggerInterface;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Support\Arrayable;

class Logger implements LoggerInterface
{
    /**
     * The underlying logger implementation.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher|null
     */
    protected $dispatcher;

    /**
     * Create a new log writer instance.
     *
     * @param  \Psr\Log\LoggerInterface  $logger
     * @param  \Illuminate\Contracts\Events\Dispatcher|null  $dispatcher
     * @return void
     */
    public function __construct(LoggerInterface $logger, Dispatcher $dispatcher = null)
    {
        $this->logger = $logger;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Log an emergency message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    /**
     * Log an alert message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    }

    /**
     * Log a critical message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->writeLog(__FUNCTION__, $message, $context);
    