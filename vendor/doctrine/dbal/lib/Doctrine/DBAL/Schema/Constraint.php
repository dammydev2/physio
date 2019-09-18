<?php

namespace Doctrine\DBAL\Sharding;

use Doctrine\DBAL\Sharding\ShardChoser\ShardChoser;
use RuntimeException;

/**
 * Shard Manager for the Connection Pooling Shard Strategy
 */
class PoolingShardManager implements ShardManager
{
    /** @var PoolingShardConnection */
    private $conn;

    /** @var ShardChoser */
    private $choser;

    /** @var string|null */
    private $currentDistributionValue;

    public function __construct(PoolingShardConnection $conn)
    {
        $params       = $conn->getParams();
        $this->conn   = $conn;
        $this->choser = $params['shardChoser'];
    }

    /**
     * {@inheritDoc}
     */
    public function selectGlobal()
    {
        $this->conn->connect(0);
        $this->currentDistributionValue = null;
    }

    /**
     * {@inheritDoc}
     */
    public function selectShard($distributionValue)
    {
        $shardId = $this->choser->pickShard($distributionValue, $this->conn);
        $this->conn->connect($shardId);
   