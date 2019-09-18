<?php

namespace Doctrine\DBAL\Connections;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Events;
use InvalidArgumentException;
use function array_rand;
use function count;
use function func_get_args;

/**
 * Master-Slave Connection
 *
 * Connection can be used with master-slave setups.
 *
 * Important for the understanding of this connection should be how and when
 * it picks the slave or master.
 *
 * 1. Slave if master was never picked before and ONLY if 'getWrappedConnection'
 *    or 'executeQuery' is used.
 * 2. Master picked when 'exec', 'executeUpdate', 'insert', 'delete', 'update', 'createSavepoint',
 *    'releaseSavepoint', 'beginTransaction', 'rollback', 'commit', 'query' or
 *    'prepare' is called.
 * 3. If master was picked once during the lifetime of the connection it will always get picked afterwards.
 * 4. One slave connection is randomly picked ONCE during a request.
 *
 * ATTENTION: You can write to the slave with this connection if you execute a write query without
 * opening up a transaction. For example:
 *
 *      $conn = DriverManager::getConnection(...);
 *      $conn->executeQuery("DELETE FROM table");
 *
 * Be aware that Connection#executeQuery is a method specifically for READ
 * operations only.
 *
 * This connection is limited to slave operations using the
 * Connection#executeQuery operation only, because it wouldn't be compatible
 * with the ORM or SchemaManager code otherwise. Both use all the other
 * operations in a context where writes could happen to a slave, which makes
 * this restricted approach necessary.
 *
 * You can manually connect to the master at any time by calling:
 *
 *      $conn->connect('master');
 *
 * Instantiation through the DriverManager looks like:
 *
 * @example
 *
 * $conn = DriverManager::getConnection(array(
 *    'wrapperClass' => 'Doctrine\DBAL\Connections\MasterSlaveConnection',
 *    'driver' => 'pdo_mysql',
 *    'master' => array('user' => '', 'password' => '', 'host' => '', 'dbname' => ''),
 *    'slaves' => array(
 *        array('user' => 'slave1', 'password', 'host' => '', 'dbname' => ''),
 *        array('user' => 'slave2', 'password', 'host' => '', 'dbname' => ''),
 *    )
 * ));
 *
 * You can also pass 'driverOptions' and any other documented option to each of this drivers to pass additional information.
 */
class MasterSlaveConnection extends Connection
{
    /**
     * Master and slave connection (one of the randomly picked slaves).
     *
     * @var DriverConnection[]|null[]
     */
    protected $connections = ['master' => null, 'slave' => null];

    /**
     * You can keep the slave connection and then switch back to it
     * during the request if you know what you are doing.
     *
     * @var bool
     */
    protected $keepSlave = false;

    /**
     * Creates Master Slave Connection.
     *
     * @param mixed[] $params
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $params, Driver $driver, ?Configuration $config = null, ?EventManager $eventManager = null)
    {
        if (! isset($params['slaves'], $params['master'])) {
            throw new InvalidArgumentException('master or slaves configuration missing');
        }
        if (count($params['slaves']) === 0) {
            throw new InvalidArgumentException('You have to configure at least one slaves.');
        }

        $params['master']['driver'] = $params['driver'];
        foreach ($params['slaves'] as $slaveKey => $slave) {
            $params['slaves'][$slaveKey]['driver'] = $params['driver'];
        }

        $this->keepSlave = (bool) ($params['keepSlave'] ?? false);

        parent::__construct($params, $driver, $config, $eventManager);
    }

    /**
     * Checks if the connection is currently towards the master or not.
     *
     * @return bool
     */
    public function isConnectedToMaster()
    {
        return $this->_conn !== null && $this->_conn === $this->connections['master'];
    }

    /**
     * {@inheritDoc}
     */
    public function connect($connectionName = null)
    {
        $requestedConnectionChange = ($connectionName !== null);
        $connectionName            = $connectionName ?: 'slave';

        if ($connectionName !== 'slave' && $connectionName !== 'master') {
            throw new InvalidArgumentException('Invalid option to connect(), only master or slave allowed.');
        }

        // If we have a connection open, and this is not an explicit connection
        // change request, then abort right here, because we are already done.
        // This prevents writes to the slave in case of "keepSlave" option enabled.
        if (isset($this->_conn) && $this->_conn && ! $requestedConnectionChange) {
            return false;
        }

        $forceMasterAsSlave = false;

        if ($this->getTransactionNestingLevel() > 0) {
            $connectionName     = 'master';
            $forceMasterAsSlave = true;
        }

        if (isset($this->connections[$connectionName]) && $this->connections[$connectionName]) {
            $this->_conn = $this->connections[$connectionName];

            if ($forceMasterAsSlave && ! $this->keepSlave) {
                $this->connections['slave'] = $this->_conn;
            }

            return false;
        }

        if ($connectionName === 'master') {
            $this->connections['master'] = $this->_conn = $this->connectTo($connectionName);

            // Set slave connection to master to avoid invalid reads
            if (! $this->keepSlave) {
