<?php

namespace Illuminate\Queue;

use Illuminate\Support\Str;
use Opis\Closure\SerializableClosure;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Connectors\SqsConnector;
use Illuminate\Queue\Connectors\NullConnector;
use Illuminate\Queue\Connectors\SyncConnector;
use Illuminate\Queue\Connectors\RedisConnector;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Queue\Connectors\DatabaseConnector;
use Illuminate\Queue\Failed\NullFailedJobProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Queue\Connectors\BeanstalkdConnector;
use Illuminate\Queue\Failed\DatabaseFailedJobProvider;

class QueueServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerManager();
        $this->registerConnection();
        $this->registerWorker();
        $this->registerListener();
        $this->registerFailedJobServices();
        $this->registerOpisSecurityKey();
    }

    /**
     * Register the queue manager.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('queue', function ($app) {
            // Once we have an instance of the queue manager, we will register the various
            // resolvers for the queue connectors. These connectors are responsible for
            // creating the classes that accept queue configs and instantiate queues.
            return tap(new QueueManager($app), function ($manager) {
                $this->registerConnectors($manager);
            });
        });
    }

    /**
     * Register the default queue connection binding.
     *
     * @return void
     */
    protected function registerConnection()
    {
        