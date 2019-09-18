<?php

namespace Doctrine\DBAL\Driver\SQLSrv;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\ServerInfoAwareConnection;
use Doctrine\DBAL\ParameterType;
use const SQLSRV_ERR_ERRORS;
use function func_get_args;
use function is_float;
use function is_int;
use function sprintf;
use function sqlsrv_begin_transaction;
use function sqlsrv_commit;
use function sqlsrv_configure;
use function sqlsrv_connect;
use function sqlsrv_errors;
use function sqlsrv_query;
use function sqlsrv_rollback;
use function sqlsrv_rows_affected;
use function sqlsrv_server_info;
use function str_replace;

/**
 * SQL Server implementation for the Connection interface.
 */
class SQLSrvConnection implements Connection, ServerInfoAwareConnection
{
    /** @var resource */
    protected $conn;

    /** @var LastInsertId */
    protected $lastInsertId;

    /**
     * @param string  $serverName
     * @param mixed[] $connectionOptions
     *
     * @throws SQLSrvException
     */
    public function __construct($serverName, $connectionOptions)
    {
        if (! sqlsrv_configure('WarningsReturnAsErrors', 0)) {
            throw SQLSrvException::fromSqlSrvErrors();