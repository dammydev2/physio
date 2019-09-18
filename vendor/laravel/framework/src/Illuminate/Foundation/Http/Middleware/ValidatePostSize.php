<?php

namespace Illuminate\Hashing;

use Illuminate\Support\Manager;
use Illuminate\Contracts\Hashing\Hasher;

class HashManager extends Manager implements Hasher
{
    /**
     * Create an instance of the Bcrypt hash Driver.
     *
     * @return \Illuminate\Hashing\BcryptHasher
     */
    public function createBcryptDriver()
    {
        return new BcryptHasher($this->app['config']['hashing.bcrypt'] ?? []);
    }

    /**
     * Create an instance of the Argon2i hash Driver.
     *
     * @return \Illuminate\Hashing\ArgonHasher
     */
    public function createArgonDriver()
    {
        return new ArgonHasher($this->app['config']['hashing.argon'] ?? []);
    }

    /**
     * Create an instance of the Argon2id hash Driver.
     *
     * @return \Illuminate\Hashing\Argon2IdHasher
     */
    public function createArgon2idDriver()
    {
        return new Argon2IdHasher($this->app['config']['hashing.argon'] ?? []);
    }

    /**
     * Get information about the given hashed value.
     *
     * @param  string  $hashedValue
     * @return array
     */
    public function info($hashedValue)
    {
        return $this->driver()->info($hashedValue);
    }

    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
