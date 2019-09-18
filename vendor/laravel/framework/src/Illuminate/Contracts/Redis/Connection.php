<?php

namespace Illuminate\Database\Connectors;

use PDO;
use Exception;
use Throwable;
use Doctrine\DBAL\Driver\PDOConnection;
use Illuminate\Database\DetectsLostConnections;

class Connector
{
    use DetectsLostConnections;

    /**
     * The default PDO connection options.
     *
     * @var array
     */
    protected $options = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    /**
     * Create a new PDO connection.
     *
     * @param  string  $dsn
     * @param  array   $config
     * @param  array   $options
     * @return \PDO
     *
     * @throws \Excepti