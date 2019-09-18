<?php

namespace Dotenv\Environment\Adapter;

use PhpOption\None;

class ApacheAdapter implements AdapterInterface
{
    /**
     * Determines if the adapter is supported.
     *
     * This happens if PHP is running as an Apache module.
     *
     * @return bool
     */
    public function isSupported()
    {
        return function_exists('apache_getenv') && function_exists('apache_setenv');
    }

    /**
     * Get an environment variable, if it exists.
     *
     * This is intentionally not implemented, since this adapter exists only as
     * a means to overwrite existing apache environment variables.
     *
     * @param string $name
     *
     * @return \PhpOption\Option
     */
    public function get($name)
    {
        return None::create();
    }

    /**
     * Set an environ