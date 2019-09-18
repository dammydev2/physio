<?php

namespace Illuminate\Console\Scheduling;

use LogicException;
use InvalidArgumentException;
use Illuminate\Contracts\Container\Container;

class CallbackEvent extends Event
{
    /**
     * The callback to call.
     *
     * @var string
     */
    protected $callback;

    /**
     * The parameters to pass to the method.
     *
     * @var array
     */
    protected $parameters;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Console\Scheduling\EventMutex  $mutex
     * @param  string  $callback
     * @param  array  $parameters
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(EventMutex $mutex, $callback, array $parameters = [])
    {
        if (! is_string($callback) && ! is_callable($callback)) {
            throw new InvalidArgumentException(
                'Invalid scheduled callback event. Must be a 