<?php

namespace Illuminate\Support\Testing\Fakes;

use BadMethodCallException;
use Illuminate\Queue\QueueManager;
use Illuminate\Contracts\Queue\Queue;
use PHPUnit\Framework\Assert as PHPUnit;

class QueueFake extends QueueManager implements Queue
{
    /**
     * All of the jobs that have been pushed.
     *
     * @var array
     */
    protected $jobs = [];

    /**
     * Assert if a job was pushed based on a truth-test callback.
     *
     * @param  string  $job
     * @param  callable|int|null  $callback
     * @return void
     */
    public function assertPushed($job, $callback = null)
    {
        if (is_numeric($callback)) {
            return $this->assertPushedTimes($job, $callback);
        }

        PHPUnit::assertTrue(
            $this->pushed($job, $callback)->count() > 0,
            "The expected [{$job}] job was not pushed."
        );
    }

    /**
     * Assert if a job was pushed a number of times.
     *
     * @param  string  $job
     * @param  int  $times
     * @return void
     */
    protected function assertPushedTimes($job, $times = 1)
    {
        PHPUnit::assertTrue(
            ($count = $this->pushed($job)->count()) === $times,
            "The expected [{$job}] job was pushed {$count} times instead of {$times} times."
        );
    }

    /**
     * Assert if a job was pushed based on a truth-test callback.
     *
     * @param  string  $queue
     * @param  string  $job
     * @param  callable|null  $callback
     * @return void
     */
    public function assertPushedOn($queue, $job, $callback = null)
    {
        return $this->assertPushed($job, function ($job, $pushedQueue) use ($callback, $queue) {
            if ($pushedQueue !== $queue) {
                return false;
            }

            return $callback ? $callback(...func_get_args()) : true;
        });
    }

    /**
     * Assert if a job was pushed with chained jobs based on a truth-test callback.
     *
     * @param  string $job
     * @param  array $expectedChain
     * @param  callable|null $callback
     * @return void
     */
    public function assertPushedWithChain($job, $expectedChain = [], $callback = null)
    {
        PHPUnit::assertTrue(
            $this->pushed($job, $callback)->isNotEmpty(),
            "The expected [{$job}] job was not pushed."
        );

        PHPUnit::assertTrue(
            collect($expectedChain)->isNotEmpty(),
            'The expected chain can not be empty.'
        );

        $this->isChainOfObjects($expectedChain)
                ? $this->assertPushedWithChainOfObjects($job, $expectedChain, $callback)
                : $this->assertPushedWithChainOfClasses($job, $expectedChain, $callback);
    }

    /**
     * Assert if a job was pushed with chained jobs based on a truth-test callback.
     *
     * @param  string $job
     * @param  array $expectedChain
     * @param  callable|null $callback
     * @return void
     */
    protected function assertPushedWithChainOfObjects($job, $expectedChain, $callback)
    {
        $chain = collect($expectedChain)->map(function ($job) {
            return serialize($job);
        })->all();

        PHPUnit::assertTrue(
            $this->pushed($job, $callback)->filter(function ($job) use ($chain) {
                return $job->chained == $chain;
            })->isNotEmpty(),
            'The expected chain was not pushed.'
        );
    }

    /**
     * Assert if a job was pushed with chained jobs based on a truth-test callback.
     *
     * @param  string $job
     * @param  array $expectedChain
 