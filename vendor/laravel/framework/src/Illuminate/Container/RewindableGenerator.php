<?php

namespace Illuminate\Database;

use Illuminate\Support\Arr;
use InvalidArgumentException;

class ConfigurationUrlParser
{
    /**
     * The drivers aliases map.
     *
     * @var array
     */
    protected static $driverAliases = [
        'mssql' => 'sqlsrv',
        'mysql2' => 'mysql', // RDS
        'postgres' => 'pgsql',
        'postgresql' => 'pgsql',
        'sqlite3' => 'sqlite',
    ];

    /**
     * Parse the database configuration, hydrating options using a database configuration URL if possible.
     *
     * @param  array|string  $config
     * @return array
     */
    public function parseConfiguration($config)
    {
        if (is_string($config)) {
            $config = ['url' => $config];
        }

        $url = $config['url'] ?? null;

        $config = Arr::except($config, 'url');

        if (! $url) {
            return $config;
        }

        $parsedUrl = $this->parseUrl($url);

        return array_merge(
            $config,
            $this->getPrimaryOptions($parsedUrl),
            $this->getQueryOptions($parsedUrl)
        