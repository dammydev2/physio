<?php

namespace Illuminate\Cache;

use Closure;
use ArrayAccess;
use DateTimeInterface;
use BadMethodCallException;
use Illuminate\Support\Carbon;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Cache\Events\KeyWritten;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Cache\Events\KeyForgotten;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Cache\Repository as CacheContract;

/**
 * @mixin \Illuminate\Contracts\Cache\Store
 */
class Repository implements CacheContract, ArrayAccess
{
    use InteractsWithTime;
    use Macroable {
        __call as macroCall;
    }

    /**
     * The cache store implementation.
     *
     * @var \Illuminate\Contracts\Cache\Store
     */
    protected $store;

    /**
     * The event dispatcher implementation.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * The default number of seconds to store items.
     *
     * @var int|null
     */
    protected $default = 3600;

    /**
     * Create a new cache repository instance.
     *
     * @param  \Illuminate\Contracts\Cache\Store  $store
     * @return void
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * Determine if an item exists in the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return ! is_null($this->get($key));
    }

    /**
     * Determine if an item doesn't exist in the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function missing($key)
    {
        return ! $this->has($key);
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (is_array($key)) {
            return $this->many($key);
        }

        $value = $this->store->get($this->itemKey($key));

        // If we could not find the cache value, we will fire the missed event and get
        // the default value for this cache value. This default could be a callback
        // so we will execute the value function which will resolve it if needed.
        if (is_null($value)) {
            $this->event(new CacheMissed($key));

            $value = value($default);
        } else {
            $this->event(new CacheHit($key, $value));
        }

        return $value;
    }

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     *
     * @param  array  $keys
     * @return array
     */
    public function many(array $keys)
    {
        $values = $this->store->many(collect($keys)->map(function ($value, $key) {
            return is_string($key) ? $key : $value;
        })->values()->all());

        return collect($values)->map(function ($value, $key) use ($keys) {
            return $this->handleManyResult($keys, $key, $value);
        })->all();
    }

    /**
     * {@inheritdoc}
     */
    public function getMultiple($keys, $default = null)
    {
        if (is_null($default)) {
            return $this->many($keys);
        }

        foreach ($keys as $key) {
            if (! isset($default[$key])) {
                $default[$key] = null;
            }
        }

        return $this->many($default);
    }

    /**
     * Handle a result for the "many" method.
     *
     * @param  array  $keys
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function handleManyResult($keys, $key, $value)
    {
        // If we could not find the cache value, we will fire the missed event and get
        // the default value for this cache value. This default could be a callback
        // so we will execute the value function which will resolve it if needed.
        if (is_null($value)) {
            $this->event(new CacheMissed($key));

            return isset($keys[$key]) ? value($keys[$key]) : null;
        }

        // If we found a valid value we will fire the "hit" event and return the value
        // back from this function. The "hit" event gives developers an opportunity
        // to listen for every possible cache "hit" throughout this applications.
        $this->event(new CacheHit($key, $value));

        return $value;
    }

    /**
     * Retrieve an item from the cache and delete it.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        return tap($this->get($key, $default), function () use ($key) {
            $this->forget($key);
        });
    }

    /**
     * Store an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  \DateTimeInterface|\DateInterval|int|null  $ttl
     * @return bool
     */
    public function put($key, $value, $ttl = null)
    {
        if (is_array($key)) {
            return $this->putMany($key, $value);
        }

        if ($ttl === null) {
            return $this->forever($key, $value);
        }

        $seconds = $this->getSeconds($ttl);

        if ($seconds <= 0) {
            return $this->delete($key);
        }

        $result = $this->store->put($this->itemKey($key), $value, $seconds);

        if ($result) {
            $this->event(new KeyWritten($key, $value, $seconds));
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = null)
    {
        return $this->put($key, $value, $ttl);
    }

    /**
     * Store multiple items in the cache for a given number of seconds.
     *
     * @param  array  $values
     * @param  \DateTimeInterface|\DateInterval|int|null  $ttl
     * @return bool
     */
    public function putMany(array $values, $ttl = null)
    {
        if ($ttl === null) {
            return $this->putManyForever($values);
        }

        $seconds = $this->getSeconds($ttl);

        if ($seconds <= 0) {
            return $this->deleteMultiple(array_keys($values));
        }

        $result = $this->store->putMany($values, $seconds);

        if ($result) {
            foreach ($values as $key => $value) {
                $this->event(new KeyWritten($key, $value, $seconds));
            }
        }

        return $result;
    }

    /**
     * Store multiple items in the cache indefinitely.
     *
     * @param  array  $values
     * @return bool
     */
    protected function putManyForever(array $values)
    {
        $result = true;

        foreach ($values as $key => $value) {
            if (! $thi