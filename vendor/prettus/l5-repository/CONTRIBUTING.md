<?php

namespace Prettus\Repository\Helpers;

/**
 * Class CacheKeys
 * @package Prettus\Repository\Helpers
 */
class CacheKeys
{

    /**
     * @var string
     */
    protected static $storeFile = "repository-cache-keys.json";

    /**
     * @var array
     */
    protected static $keys = null;

    /**
     * @param $group
     * @param $key
     *
     * @return void
     */
    public static function putKey($group, $key)
    {
        self::loadKeys();

        self::$keys[$group] = self::getKeys($group);

        if (!in_array($key, self::$keys[$group])) {
            self::$keys[$group][] = $key;
        }

        self::storeKeys();
    }

    /**
     * @return array|mixed
     */
    public static function loadKeys()
    {
        if (!is_null(self::$keys) && is_array(self::$keys)) {
            return self::$keys;
        }

        $file = self::getFileKeys();

        if (!file_exists($file)) {
         