<?php

namespace Doctrine\DBAL\Driver\SQLSrv;

use Doctrine\DBAL\Driver\AbstractSQLServerDriver;

/**
 * Driver for ext/sqlsrv.
 */
class Driver extends AbstractSQLServerDriver
{
    /**
     * {@inheritdoc}
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        if (! isset($params['host'])) {
            throw new SQLSrvException("Missing 'host' in configuration for sqlsrv driver.");
        }

        $serverName = $params['host'];
        if (isset($params['port'])) {
            $serverName .= ', ' . $params['port'];
        }

        if (isset($params['dbname'])) {
            $driverOptions['Database'] = $params['dbname'];
        }

        if (isset($params['charset'])) {
            $driverOptions['Chara