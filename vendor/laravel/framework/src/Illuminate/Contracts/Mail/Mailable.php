<?php

namespace Illuminate\Database\Capsule;

use PDO;
use Illuminate\Container\Container;
use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Traits\CapsuleManagerTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Connectors\ConnectionFactory;

class Manager
{
    use CapsuleManagerTrait;

    /**
     * The database manager instance.
     *
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $manager;

    /**
     * Create a new database capsule manager.
     *
     * @param  \Illuminate\Container\Container|null  $container
     * @return void
     */
    public function __construct(Container $container = null)
    {
        $this->setup