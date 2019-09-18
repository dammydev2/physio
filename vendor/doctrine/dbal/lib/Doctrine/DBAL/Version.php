<?php

namespace Doctrine\DBAL\Driver\PDOSqlsrv;

use Doctrine\DBAL\Driver\AbstractSQLServerDriver;
use function is_int;
use function sprintf;

/**
 * The PDO-based Sqlsrv driver.
 */
class Driver extends AbstractSQLServerDriver
{
    /**
     * {@inheritdoc}
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        [$driverOptions, $connectionOptions] = $this->splitOptions($driverOptions);

        return new Connection(
            $this->_constructPdoDsn($params, $connectionOptions),
            $username,
            $password,
            $driverOptions
        );
    }

    /**
     * Constructs the Sqlsrv PDO DSN.
     *
     * @param mixed[]  $params
     * @param string[] $connectionOptions
  