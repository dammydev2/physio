<?php

namespace Illuminate\Cache;

use Memcached;

class MemcachedConnector
{
    /**
     * Create a new Memcached connection.
     *
     * @param  array  $servers
     * @param  string|null  $connectionId
     * @param  array  $options
     * @param  array  $credentials
     * @return \Memcached
     */
    public function connect(array $servers, $connectionId = null, array $options = [], array $credentials = [])
    {
        $memcached = $this->getMemcached(
            $connectionId, $credentials, $options
        );

        if (! $memcached->getServerList()) {
            // For each server in the array, we'll just extract the configuration and add
            // the server to the Memcached connection. Once we have added all of these
            // servers we'll verify the connection is successful and return 