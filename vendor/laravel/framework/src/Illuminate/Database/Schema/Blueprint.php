<?php

namespace Illuminate\Events;

use Exception;
use ReflectionClass;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastFactory;
use Illuminate\Contracts\Container\Container as ContainerContract;

class Dispatcher implements DispatcherContract
{
    /**
     * The IoC container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * The registered event listeners.
     *
     * @var array
     */
    protected $listeners = [];

    /**
     * The wildcard listeners.
     *
     * @var array
     */
    protected $wildcards = [];

    /**
     * The cached wildcard listeners.
     *
     * @var array
     */
    protected $wildcardsCache = [];

    /**
     * The queue resolver instance.
     *
     * @var callable
     */
    protected $queueResolver;

    /**
     * Create a new event dispatcher instance.
     *
     * @param  \Illuminate\Contracts\Container\Container|null  $container
     * @return void
     */
    public function __construct(ContainerContract $container = null)
    {
        $this->container = $container ?: new Container;
    }

    /**
     * Register an event listener with the dispatcher.
     *
     * @param  string|array  $events
     * @param  mixed  $listener
     * @return void
     */
    public function listen($events, $listener)
    {
        foreach ((array) $events as $event) {
            if (Str::contains($event, '*')) {
                $this->setupWildcardListen($event, $listener);
            } else {
                $this->listeners[$event][] = $this->makeListener($listener);
            }
        }
    }

    /**
     * Setup a wildcard listener callback.
     *
     * @param  string  $event
     * @param  mixed  $listener
     * @return void
     */
    protected function setupWildcardListen($event, $listener)
    {
        $this->wildcards[$event][] = $this->makeListener($listener, true);

        $this->wildcardsCache = [];
    }

    /**
     * Determine if a given event has listeners.
     *
     * @param  string  $eventName
     * @return bool
     */
    public function hasListeners($eventName)
    {
        return isset($this->listeners[$eventName]) || isset($this->wildcards[$eventName]);
    }

    /**
     * Register an event and payload to be fired later.
     *
     * @param  string  $event
     * @param  array  $payload
     * @return void
     */
    public function push($event, $payload = [])
    {
        $this->listen($event.'_pushed', function () use ($event, $payload) {
            $this->dispatch($event, $payload);
        });
    }

    /**
     * Flush a set of pushed events.
     *
     * @param  string  $event
     * @return void
     */
    public function flush($event)
    {
        $this->dispatch($event.'_pushed');
    }

    /**
     * Register an event subscriber with the dispatcher.
     *
     * @param  object|string  $subscriber
     * @return void
     */
    public function subscribe($subscriber)
    {
        $subscriber = $this->resolveSubscriber($subscriber);

        $subscriber->subscribe($this);
    }

    /**
     * Resolve the subscriber instance.
     *
     * @param  object|string  $subscriber
     * @return mixed
     */
    protected function resolveSubscriber($subscriber)
    {
        if (is_string($subscriber)) {
            return $this->container->make($subscriber);
        }

        return $subscriber;
    }

    /**
     * Fire an event until the first non-null response is returned.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @return array|null
     */
    public function until($event, $payload = [])
    {
        return $this->dispatch($event, $payload, true);
    }

    /**
     * Fire an event and call the listeners.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     * @return array|null
     */
    public function dispatch($event, $payload = [], $halt = false)
    {
        // When the given "event" is actually an object we will assume it is an event
        // object and use the class as the event name and this event itself as the
        // payload to the handler, which makes object based events quite simple.
        [$event, $payload] = $this->parseEventAndPayload(
            $event, $payload
        );

        if ($this->shouldBroadcast($payload)) {
            $this->broadcastEvent($payload[0]);
        }

        $responses = [];

        foreach ($this->getListeners($event) as $listener) {
            $response = $listener($event, $payload);

            // If a response is returned from the listener and event halting is enabled
            // we will just return this response, and not call the rest of the event
            // listeners. Otherwise we will add the response on the response list.
            if ($halt && ! is_null($response)) {
                return $response;
            }

            // If a boolean false is returned from a listener, we will stop propagating
            // the event to any further listeners down in the chain, else we keep on
            // looping through the listeners and firing every one in our sequence.
            if ($response === false) {
                break;
            }

            $responses[] = $response;
        }

        return $halt ? null : $responses;
    }

    /**
     * Parse the given event and payload and prepare them for dispatching.
     *
     * @param  mixed  $event
     * @param  mixed  $payload
     * @return array
     */
    protected function parseEventAndPayload($event, $payload)
    {
        if (is_object($event)) {
            [$payload, $event] = [[$event], get_class($event)];
        }

        return [$event, Arr::wrap($payload)];
    }

    /**
     * Determine if the payload has a broadcastable event.
     *
     * @param  array  $payload
     * @return bool
     */
    protected function shouldBroadcast(array $payload)
    {
        return isset($payload[0]) &&
               $payload[0] instanceof ShouldBroadcast &&
               $this->broadcastWhen($payload[0]);
    }

    /**
     * Check if event should be broadcasted by condition.
     *
     * @param  mixed  $event
     * @return bool
     */
    protected function broadcastWhen($event)
    {
        return method_exists($event, 'broadcastWhen')
                ? $event->broadcastWhen() : true;
    }

    /**
     * Broadcast the given event class.
     *
     * @param  \Illuminate\Contracts\Broadcasting\ShouldBroadcast  $event
     * @return void
     */
    protected function broadcastEvent($event)
    {
        $this->container->make(BroadcastFactory::class)->queue($event);
    }

    /**
     * Get all of the listeners for a given event name.
     *
     * @param  string  $eventName
     * @return array
     */
    public function getListeners($eventName)
    {
        $listeners = $this->listeners[$eventName] ?? [];

        $listeners = array_merge(
            $listeners,
            $this->wildcardsCache[$eventName] ?? $this->getWildcardListeners($eventName)
        );

        return class_exists($eventName, false)
                    ? $this->addInterfaceListeners($eventName, $listeners)
                    : $listeners;
    }

    /**
     * Get the wildcard listeners for the event.
     *
     * @param  string  $eventName
     * @return array
     */
    protected function getWildcardListeners($eventName)
    {
        $wildcards = [];

        foreach ($this->wildcards as $key => $listeners) {
            if (Str::is($key, $eventName)) {
                $wildcards = array_merge($wildcards, $listeners);
            }
        }

        return $this->wildcardsCache[$eventName] = $wildcards;
    }

    /**
     * Add the listeners for the event's interfaces to the given array.
     *
     * @param  string  $eventName
     * @param  array  $listeners
     * @return array
     */
    protected function addInterfaceListeners($eventName, array $listeners = [])
    {
        foreach (class_implements($eventName) as $interface) {
            if (isset($this->listeners[$interface])) {
                foreach ($this->listeners[$interface] as $names) {
                    $listeners = array_merge($listeners, (array) $names);
                }
            }
        }

        return $listeners;
    }

    /**
     * Register an event listener with the dispatcher.
     *
     * @param  \Closure|string  $listener
     * @param  bool  $wildcard
     * @return \Closure
     */
    public function makeListener($listener, $wildcard = false)
    {
        if (is_string($listener)) {
            return $this->createClassListener($listener, $wildcard);
        }

        return function ($event, $payload) use ($listener, $wildcard) {
            if ($wildcard) {
                return $listener($event, $payload);
            }

            return $listener(...array_values($payload));
        };
    }

    /**
     * Create a class based listener using the IoC container.
     *
     * @param  string  $listener
     * @param  bool  $wildcard
     * @return \Closure
     */
    public function createClassListener($listener, $wildcard = false)
    {
        return function ($event, $payload) use ($listener, $wildcard) {
            if ($wildcard) {
                return call_user_func($this->createClassCallable($listener), $event, $payload);
            }

            return call_user_func_array(
                $this->createClassCallable($listener), $payload
            );
        };
    }

    /**
     * Create the class based event callable.
     *
     * @param  string  $listener
     * @return callable
     */
    protected function createClassCallable($listener)
    {
        [$class, $method] = $this->parseClassCallable($listener);

        if ($this->handlerShouldBeQueued($class)) {
            return $this->createQueuedHandlerCallable($class, $method);
        }

        return [$this->container->make($class), $method];
    }

    /**
     * Parse the class listener into class and method.
     *
     * @param  string  $listener
     * @return array
     */
    protected function parseClassCallable($listener)
    {
        return Str::parseCallback($listener, 'handle');
    }

    /**
     * Determine if the event handler class should be queued.
     *
     * @param  string  $class
     * @return bool
     */
    protected function handlerShouldBeQueued($class)
    {
        try {
            return (new ReflectionClass($class))->implementsInterface(
                ShouldQueue::class
            );
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Create a callable for putting an event handler on the queue.
     *
     * @param  string  $class
     * @param  string  $method
     * @return \Closure
     */
    protected function createQueuedHandlerCallable($class, $method)
    {
        return function () use ($class, $method) {
            $arguments = array_map(function ($a) {
                return is_object($a) ? clone $a : $a;
            }, func_get_args());

            if ($this->handlerWantsToBeQueued($class, $arguments)) {
                $this->queueHandler($class, $method, $arguments);
            }
        };
    }

    /**
     * Determine if the event handler wants to be queued.
     *
     * @param  string  $class
     * @param  array  $arguments
     * @return bool
     */
    protected function handlerWantsToBeQueued($class, $arguments)
    {
        if (method_exists($class, 'shouldQueue')) {
            return $this->container->make($class)->shouldQueue($arguments[0]);
        }

        return true;
    }

    /**
     * Queue the handler class.
     *
     * @param  string  $class
     * @param  string  $method
     * @param  array  $arguments
     * @return void
     */
    protected function queueHandler($class, $method, $arguments)
    {
        [$listener, $job] = $this->createListenerAndJob($class, $method, $arguments);

        $connection = $this->resolveQueue()->connection(
            $listener->connection ?? null
        );

        $queue = $listener->queue ?? null;

        isset($listener->delay)
                    ? $connection->laterOn($queue, $listener->delay, $job)
                    : $connection->pushOn($queue, $job);
    }

    /**
     * Create the listener and job for a queued listener.
     *
     * @param  string  $class
     * @param  string  $method
     * @param  array  $arguments
     * @return array
     */
    protected function createListenerAndJob($class, $method, $arguments)
    {
        $listener = (new ReflectionClass($class))->newInstanceWithoutConstructor();

        return [$listener, $this->propagateListenerOptions(
            $listener, new CallQueuedListener($class, $method, $arguments)
        )];
    }

    /**
     * Propagate listener options to the job.
     *
     * @param  mixed  $listener
     * @param  mixed  $job
     * @return mixed
     */
    protected function propagateListenerOptions($listener, $job)
    {
        return tap($job, function ($job) use ($listener) {
            $job->tries = $listener->tries ?? null;
            $job->timeout = $listener->timeout ?? null;
            $job->timeoutAt = method_exists($listener, 'retryUntil')
                                ? $listener->retryUntil() : null;
        });
    }

    /**
     * Remove a set of listeners from the dispatcher.
     *
     * @param  string  $event
     * @return void
     */
    public function forget($event)
    {
        if (Str::contains($event, '*')) {
            unset($this->wildcards[$event]);
        } else {
            unset($this->listeners[$event]);
        }
    }

    /**
     * Forget all of the pushed listeners.
     *
     * @return void
     */
    public function forgetPushed()
    {
        foreach ($this->listeners as $key => $value) {
            if (Str::endsWith($key, '_pushed')) {
                $this->forget($key);
            }
        }
    }

    /**
     * Get the queue implementation from the resolver.
     *
     * @return \Illuminate\Contracts\Queue\Queue
     */
    protected function resolveQueue()
    {
        return call_user_func($this->queueResolver);
    }

    /**
     * Set the queue resolver implementation.
     *
     * @param  callable  $resolver
     * @return $this
     */
    public function setQueueResolver(callable $resolver)
    {
        $this->queueResolver = $resolver;

        return $this;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               The MIT License (MIT)

Copyright (c) Taylor Otwell

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php

namespace Illuminate\Filesystem;

use Illuminate\Contracts\Cache\Repository;
use League\Flysystem\Cached\Storage\AbstractCache;

class Cache extends AbstractCache
{
    /**
     * The cache repository implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $repository;

    /**
     * The cache key.
     *
     * @var string
     */
    protected $key;

    /**
     * The cache expiration time in seconds.
     *
     * @var int|null
     */
    protected $expire;

    /**
     * Create a new cache instance.
     *
     * @param \Illuminate\Contracts\Cache\Repository  $repository
     * @param string  $key
     * @param int|null  $expire
     */
    public function __construct(Repository $repository, $key = 'flysystem', $expire = null)
    {
        $this->key = $key;
        $this->expire = $expire;
        $this->repository = $repository;
    }

    /**
     * Load the cache.
     *
     * @return void
     */
    public function load()
    {
        $contents = $this->repository->get($this->key);

        if (! is_null($contents)) {
            $this->setFromStorage($contents);
        }
    }

    /**
     * Persist the cache.
     *
     * @return void
     */
    public function save()
    {
        $contents = $this->getForStorage();

        $this->repository->put($this->key, $contents, $this->expire);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      {
    "name": "illuminate/filesystem",
    "description": "The Illuminate Filesystem package.",
    "license": "MIT",
    "homepage": "https://laravel.com",
    "support": {
        "issues": "https://github.com/laravel/framework/issues",
        "source": "https://github.com/laravel/framework"
    },
    "authors": [
        {
            "name": "Taylor Otwell",
            "email": "taylor@laravel.com"
        }
    ],
    "require": {
        "php": "^7.1.3",
        "illuminate/contracts": "5.8.*",
        "illuminate/support": "5.8.*",
        "symfony/finder": "^4.2"
    },
    "autoload": {
        "psr-4": {
            "Illuminate\\Filesystem\\": ""
        }
    },
    "extra": {
        "branch-alias": {
            "dev-master": "5.8-dev"
        }
    },
    "suggest": {
        "league/flysystem": "Required to use the Flysystem local and FTP drivers (^1.0).",
        "league/flysystem-aws-s3-v3": "Required to use the Flysystem S3 driver (^1.0).",
        "league/flysystem-cached-adapter": "Required to use the Flysystem cache (^1.0).",
        "league/flysystem-rackspace": "Required to use the Flysystem Rackspace driver (^1.0).",
        "league/flysystem-sftp": "Required to use the Flysystem SFTP driver (^1.0)."
    },
    "config": {
        "sort-packages": true
    },
    "minimum-stability": "dev"
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <?php

namespace Illuminate\Filesystem;

use ErrorException;
use FilesystemIterator;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class Filesystem
{
    use Macroable;

    /**
     * Determine if a file or directory exists.
     *
     * @param  string  $path
     * @return bool
     */
    public function exists($path)
    {
        return file_exists($path);
    }

    /**
     * Get the contents of a file.
     *
     * @param  string  $path
     * @param  bool  $lock
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get($path, $lock = false)
    {
        if ($this->isFile($path)) {
            return $lock ? $this->sharedGet($path) : file_get_contents($path);
        }

        throw new FileNotFoundException("File does not exist at path {$path}");
    }

    /**
     * Get contents of a file with shared access.
     *
     * @param  string  $path
     * @return string
     */
    public function sharedGet($path)
    {
        $contents = '';

        $handle = fopen($path, 'rb');

        if ($handle) {
            try {
                if (flock($handle, LOCK_SH)) {
                    clearstatcache(true, $path);

                    $contents = fread($handle, $this->size($path) ?: 1);

                    flock($handle, LOCK_UN);
                }
            } finally {
                fclose($handle);
            }
        }

        return $contents;
    }

    /**
     * Get the returned value of a file.
     *
     * @param  string  $path
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getRequire($path)
    {
        if ($this->isFile($path)) {
            return require $path;
        }

        throw new FileNotFoundException("File does not exist at path {$path}");
    }

    /**
     * Require the given file once.
     *
     * @param  string  $file
     * @return mixed
     */
    public function requireOnce($file)
    {
        require_once $file;
    }

    /**
     * Get the MD5 hash of the file at the given path.
     *
     * @param  string  $path
     * @return string
     */
    public function hash($path)
    {
        return md5_file($path);
    }

    /**
     * Write the contents of a file.
     *
     * @param  string  $path
     * @param  string  $contents
     * @param  bool  $lock
     * @return int|bool
     */
    public function put($path, $contents, $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    /**
     * Write the contents of a file, replacing it atomically if it already exists.
     *
     * @param  string  $path
     * @param  string  $content
     * @return void
     */
    public function replace($path, $content)
    {
        // If the path already exists and is a symlink, get the real path...
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        $tempPath = tempnam(dirname($path), basename($path));

        // Fix permissions of tempPath because `tempnam()` creates it with permissions set to 0600...
        chmod($tempPath, 0777 - umask());

        file_put_contents($tempPath, $content);

        rename($tempPath, $path);
    }

    /**
     * Prepend to a file.
     *
     * @param  string  $path
     * @param  string  $data
     * @return int
     */
    public function prepend($path, $data)
    {
        if ($this->exists($path)) {
            return $this->put($path, $data.$this->get($path));
        }

        return $this->put($path, $data);
    }

    /**
     * Append to a file.
     *
     * @param  string  $path
     * @param  string  $data
     * @return int
     */
    public function append($path, $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    /**
     * Get or set UNIX mode of a file or directory.
     *
     * @param  string  $path
     * @param  int  $mode
     * @return mixed
     */
    public function chmod($path, $mode = null)
    {
        if ($mode) {
            return chmod($path, $mode);
        }

        return substr(sprintf('%o', fileperms($path)), -4);
    }

    /**
     * Delete the file at a given path.
     *
     * @param  string|array  $paths
     * @return bool
     */
    public function delete($paths)
    {
        $paths = is_array($paths) ? $paths : func_get_args();

        $success = true;

        foreach ($paths as $path) {
            try {
                if (! @unlink($path)) {
                    $success = false;
                }
            } catch (ErrorException $e) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Move a file to a new location.
     *
     * @param  string  $path
     * @param  string  $target
     * @return bool
     */
    public function move($path, $target)
    {
        return rename($path, $target);
    }

    /**
     * Copy a file to a new location.
     *
     * @param  string  $path
     * @param  string  $target
     * @return bool
     */
    public function copy($path, $target)
    {
        return copy($path, $target);
    }

    /**
     * Create a hard link to the target file or directory.
     *
     * @param  string  $target
     * @param  string  $link
     * @return void
     */
    public function link($target, $link)
    {
        if (! windows_os()) {
            return symlink($target, $link);
        }

        $mode = $this->isDirectory($target) ? 'J' : 'H';

        exec("mklink /{$mode} \"{$link}\" \"{$target}\"");
    }

    /**
     * Extract the file name from a file path.
     *
     * @param  string  $path
     * @return string
     */
    public function name($path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * Extract the trailing name component from a file path.
     *
     * @param  string  $path
     * @return string
     */
    public function basename($path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    /**
     * Extract the parent directory from a file path.
     *
     * @param  string  $path
     * @return string
     */
    public function dirname($path)
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    /**
     * Extract the file extension from a file path.
     *
     * @param  string  $path
     * @return string
     */
    public function extension($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Get the file type of a given file.
     *
     * @param  string  $path
     * @return string
     */
    public function type($path)
    {
        return filetype($path);
    }

    /**
     * Get the mime-type of a given file.
     *
     * @param  string  $path
     * @return string|false
     */
    public function mimeType($path)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
    }

    /**
     * Get the file size of a given file.
     *
     * @param  string  $path
     * @return int
     */
    public function size($path)
    {
        return filesize($path);
    }

    /**
     * Get the file's last modification time.
     *
     * @param  string  $path
     * @return int
     */
    public function lastModified($path)
    {
        return filemtime($path);
    }

    /**
     * Determine if the given path is a directory.
     *
     * @param  string  $directory
     * @return bool
     */
    public function isDirectory($directory)
    {
        return is_dir($directory);
    }

    /**
     * Determine if the given path is readable.
     *
     * @param  string  $path
     * @return bool
     */
    public function isReadable($path)
    {
        return is_readable($path);
    }

    /**
     * Determine if the given path is writable.
     *
     * @param  string  $path
     * @return bool
     */
    public function isWritable($path)
    {
        return is_writable($path);
    }

    /**
     * Determine if the given path is a file.
     *
     * @param  string  $file
     * @return bool
     */
    public function isFile($file)
    {
        return is_file($file);
    }

    /**
     * Find path names matching a given pattern.
     *
     * @param  string  $pattern
     * @param  int     $flags
     * @return array
     */
    public function glob($pattern, $flags = 0)
    {
        return glob($pattern, $flags);
    }

    /**
     * Get an array of all files in a directory.
     *
     * @param  string  $directory
     * @param  bool  $hidden
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    public function files($directory, $hidden = false)
    {
        return iterator_to_array(
            Finder::create()->files()->ignoreDotFiles(! $hidden)->in($directory)->depth(0)->sortByName(),
            false
        );
    }

    /**
     * Get all of the files from the given directory (recursive).
     *
     * @param  string  $directory
     * @param  bool  $hidden
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    public function allFiles($directory, $hidden = false)
    {
        return iterator_to_array(
            Finder::create()->files()->ignoreDotFiles(! $hidden)->in($directory)->sortByName(),
            false
        );
    }

    /**
     * Get all 