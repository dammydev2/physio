<?php

namespace Doctrine\DBAL\Driver\PDOPgSql;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\AbstractPostgreSQLDriver;
use Doctrine\DBAL\Driver\PDOConnection;
use PDO;
use PDOException;
use function defined;

/**
 * Driver that connects through pdo_pgsql.
 */
class Driver extends AbstractPostgreSQLDriver
{
    /**
     * {@inheritdoc}
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        try {
            $pdo = new PDOConnection(
                $this->_constructPdoDsn($params),
                $username,
                $password,
                $driverOptions
            );

            if (defined('PDO::PGSQL_ATTR_DISABLE_PREPARES')
                