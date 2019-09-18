<?php

namespace Illuminate\Queue\Console;

use Illuminate\Queue\Worker;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\WorkerOptions;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

class WorkCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'queue:work
                            {connection? : The name of the queue connection to work}
                            {--queue= : The names of the queues to work}
                            {--daemon : Run the worker in daemon mode (Deprecated)}
                            {--once : Only process the next job on the queue}
                            {--stop-when-empty : Stop when the queue is empty}
                            {--delay=0 : The number of seconds to delay failed j