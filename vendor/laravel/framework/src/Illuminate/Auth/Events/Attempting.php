<?php

namespace Illuminate\Cache;

use Illuminate\Support\Str;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Contracts\Cache\Lock as LockContract;
use Illuminate\Contracts\Cache\LockTimeoutException;

abstract class Lock implements LockContract
{
    use InteractsWithTime;

    /**
     * The name of the lock.
     *
     * @var string
     */
    protected $name;

    /**
     * The number of seconds the lock should be maintained.
     *
     * @var int
     */
    protected $seconds;

    /**
     * The scope identifier of this lock.
     *
     * @var string
     */
    protected $owner;

    /**
     * Create a new lock instance.
     *
     * @param  string  $name
     * @param  int  $seconds
     * @param  string|null 