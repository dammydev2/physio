<?php

namespace Illuminate\Redis\Connectors;

use Redis;
use RedisCluster;
use Illuminate\Support\Arr;
use Illuminate\Redis\Connections\PhpRedisConnection;
use Illuminate\Redis\Connections\PhpRedisClusterConnection;

class PhpRedisConnector
{
    /**
     * Create a new clustered PhpRedis connection.
     *
     * @param  array  $config
     * @param  array  $options
     * @return \Illuminate\Redis\Connections\PhpRedisConnection
     */
    public function connect(array $config, array $options)
    {
        return new PhpRedisConnection($this->createClient(array_merge(
            $config, $options, Arr::pull($config, 'options', [])
        )));
    }

    /**
     * Create a new clustered PhpRedis connection.
     *
     * @param  array  $config
     * @param  array  $clusterOptions
     * @param  array  $options
     * @return \Illuminate\Redis\Connections\PhpRedisClusterConnection
     */
    public function connectToCluster(array $config, array $clusterOptions, array $options)
    {
        $options = array_merge($options, $clusterOptions, Arr::pull($config, 'options', []));

        return new PhpRedisClusterConnection($this->createRedisClusterInstance(
            array_map([$this, 'buildC