<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\VarDumper\Caster;

use Symfony\Component\VarDumper\Cloner\Stub;

/**
 * Casts Redis class from ext-redis to array representation.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class RedisCaster
{
    private static $serializer = [
        \Redis::SERIALIZER_NONE => 'NONE',
        \Redis::SERIALIZER_PHP => 'PHP',
        2 => 'IGBINARY', // Optional Redis::SERIALIZER_IGBINARY
    ];

    private static $mode = [
        \Redis::ATOMIC => 'ATOMIC',
        \Redis::MULTI => 'MULTI',
        \Redis::PIPELINE => 'PIPELINE',
    ];

    private static $compression = [
        0 => 'NONE', // Redis::COMPRESSION_NONE
        1 => 'LZF',  // Redis::COMPRESSION_LZF
    ];

    private static $failover = [
        \RedisCluster::FAILOVER_NONE => 'NONE',
        \RedisCluster::FAILOVER_ERROR => 'ERROR',
        \RedisCluster::FAILOVER_DISTRIBUTE => 'DISTRIBUTE',
        \RedisCluster::FAILOVER_DISTRIBUTE_SLAVES => 'DISTRIBUTE_SLAVES',
    ];

    public static function castRedis(\Redis $c, array $a, Stub $stub, $isNested)
    {
        $prefix = Caster::PREFIX_VIRTUAL;

        if (!$connected = $c->isConnected()) {
            return $a + [
                $prefix.'isConnected' => $connected,
            ];
        }

        $mode = $c->getMode();

        return $a + [
            $prefix.'isConnected' => $connected,
            $prefix.'host' => $c->getHost(),
            $prefix.'port' => $c->getPort(),
            $prefix.'auth' => $c->getAuth(),
            $prefix.'mode' => isset(self::$mode[$mode]) ? new ConstStub(self::$mode[$mode], $mode) : $mode,
            $prefix.'dbNum' => $c->getDbNum(),
            $prefix.'timeout' => $c->getTimeout(),
            $prefix.'lastError' => $c->getLastError(),
            $prefix.'persistentId' => $c->getPersistentID(),
            $prefix.'options' => self::getRedisOptions($c),
        ];
    }

    public static function castRedisArray(\RedisArray $c, array $a, Stub $stub, $isNested)
    {
        $prefix = Caster::PREFIX_VIRTUAL;

        return $a + [
            $prefix.'hosts' => $c->_hosts(),
            $prefix.'function' => ClassStub::wrapCallable($c->_function()),
            $prefix.'lastError' => $c->getLastError(),
            $prefix.'