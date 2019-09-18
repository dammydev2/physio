<?php

namespace Doctrine\DBAL;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Driver\DrizzlePDOMySql\Driver as DrizzlePDOMySQLDriver;
use Doctrine\DBAL\Driver\IBMDB2\DB2Driver;
use Doctrine\DBAL\Driver\Mysqli\Driver as MySQLiDriver;
use Doctrine\DBAL\Driver\OCI8\Driver as OCI8Driver;
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySQLDriver;
use Doctrine\DBAL\Driver\PDOOracle\Driver as PDOOCIDriver;
use Doctrine\DBAL\Driver\PDOPgSql\Driver as PDOPgSQLDriver;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as PDOSQLiteDriver;
use Doctrine\DBAL\Driver\PDOSqlsrv\Driver as PDOSQLSrvDriver;
use Doctrine\DBAL\Driver\SQLAnywhere\Driver as SQLAnywhereDriver;
use Doctrine\DBAL\Driver\SQLSrv\Driver as SQLSrvDriver;
use PDO;
use function array_keys;
use function array_map;
use function array_merge;
use function class_implements;
use function in_array;
use function is_subclass_of;
use function parse_str;
use function parse_url;
use function preg_replace;
use function str_replace;
use function strpos;
use function substr;

/**
 * Factory for creating Doctrine\DBAL\Connection instances.
 */
final class DriverManager
{
    /**
     * List of supported drivers and their mappings to the driver classes.
     *
     * To add your own driver use the 'driverClass' parameter to
     * {@link DriverManager::getConnection()}.
     *
     * @var string[]
     */
    private static $_driverMap = [
        'pdo_mysql'          => PDOMySQLDriver::class,
        'pdo_sqlite'         => PDOSQLiteDriver::class,
        'pdo_pgsql'          => PDOPgSQLDriver::class,
        'pdo_oci'            => PDOOCIDriver::class,
        'oci8'               => OCI8Driver::class,
        'ibm_db2'            => DB2Driver::class,
        'pdo_sqlsrv'         => PDOSQLSrvDriver::class,
        'mysqli'             => MySQLiDriver::class,
        'drizzle_pdo_mysql'  => DrizzlePDOMySQLDriver::class,
        'sqlanywhere'        => SQLAnywhereDriver::class,
        'sqlsrv'             => SQLSrvDriver::class,
    ];

    /**
     * List of URL schemes from a database URL and their mappings to driver.
     *
     * @var string[]
     */
    private static $driverSchemeAliases = [
        'db2'        => 'ibm_db2',
        'mssql'      => 'pdo_sqlsrv',
        'mysql'      => 'pdo_mysql',
        'mysql2'     => 'pdo_mysql', // Amazon RDS, for some weird reason
        'postgres'   => 'pdo_pgsql',
        'postgresql' => 'pdo_pgsql',
        'pgsql'      => 'pdo_pgsql',
        'sqlite'     => 'pdo_sqlite',
        'sqlite3'    => 'pdo_sqlite',
    ];

    /**
     * Private constructor. This class cannot be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * Creates a connection object based on the specified parameters.
     * This method returns a Doctrine\DBAL\Connection which wraps the underlying
     * driver connection.
     *
     * $params must contain at least one of the following.
     *
     * Either 'driver' with one of the following values:
     *
     *     pdo_mysql
     *     pdo_sqlite
     *     pdo_pgsql
     *     pdo_oci (unstable)
     *     pdo_sqlsrv
     *     pdo_sqlsrv
     *     mysqli
     *     sqlanywhere
     *     sqlsrv
     *     ibm_db2 (unstable)
     *     drizzle_pdo_mysql
     *
     * OR 'driverClass' that contains the full class name (with namespace) of the
     * driver class to instantiate.
     *
     * Other (optional) parameters:
     *
     * <b>user (string)</b>:
     * The username to use when connecting.
     *
     * <b>password (string)</b>:
     * The password to use when connecting.
     *
     * <b>driverOptions (array)</b>:
     * Any additional driver-specific options for the driver. These are just passed
     * through to the driver.
     *
     * <b>pdo</b>:
     * You can pass an existing PDO instance through this parameter. The PDO
     * instance will be wrapped in a Doctrine\DBAL\Connection.
     *
     * <b>wrapperClass</b>:
     * You may specify a custom wrapper class through the 'wrapperClass'
     * parameter but this class MUST inherit from Doctrine\DBAL\Connection.
     *
     * <b>driverClass</b>:
     * The driver class to use.
     *
     * @param mixed[]            $params       The parameters.
     * @param Configuration|null $config       The configuration to use.
     * @param EventManager|null  $eventManager The event manager to use.
     *
     * @throws DBALExc