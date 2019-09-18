<?php

namespace Illuminate\Events;

use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CallQueuedListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The listener class name.
     *
     * @var string
     */
    public $class;

    /**
     * The listener method.
     *
     * @var string
     */
    public $method;

    /**
     * The data to be passed to the listener.
     *
     * @var array
     */
    public $data;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries;

    /**
     * The timestamp indicating when the job should timeout.
     *
     * @var int
     */
    public $timeoutAt;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout;

    /**
     * Create a new job instance.
     *
     * @param  string  $class
     * @param  string  $method
     * @param  array  $data
     * @return void
     */
    public function __construct($clas