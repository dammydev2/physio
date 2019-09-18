Introduction
============

Doctrine Cache is a library that provides an interface for caching data.
It comes with implementations for some of the most popular caching data
stores. Here is what the ``Cache`` interface looks like.

.. code-block:: php
    namespace Doctrine\Common\Cache;

    interface Cache
    {
        public function fetch($id);
        public function contains($id);
        public function save($id, $data, $lifeTime = 0);
        public function delete($id);
        public function getStats();
    }

Here is an example that uses Memcache.

.. code-block:: php
    use Doctrine\Common\Cache\MemcacheCache;

    $memcache = new Memcache();
    $cache = new MemcacheCache();
    $cache->setMemcache($memcache);

    $cache->set('key', 'value');

    echo $cache->get('key') // prints "value"

Drivers
=======

Doctrine ships with several common drivers that you can easily use.
Below you can find information about all the available drivers.

ApcCache
--------

The ``ApcCache`` driver uses the ``apc_fetch``, ``apc_exists``, etc. functions that come
with PHP so no additional setup is required in order to use it.

.. code-block:: php
    $cache = new ApcCache();

ApcuCache
---------

The ``ApcuCache`` driver uses the ``apcu_fetch``, ``apcu_exists``, etc. functions that come
with PHP so no additional setup is required in order to use it.

.. code-block:: php
    $cache = new ApcuCache();

ArrayCache
----------

The ``ArrayCache`` driver stores the cache data in PHPs memory and is not persisted anywhere.
This can be useful for caching things in memory for a single process when you don't need
the cache to be persistent across processes.

.. code-block:: php
    $cache = new ArrayCache();

ChainCache
----------

The ``ChainCache`` driver lets you chain multiple other drivers together easily.

.. code-block:: php
    $arrayCache = new ArrayCache();
    $apcuCache = new ApcuCache();

    $cache = new ChainCache([$arrayCache, $apcuCache]);

CouchbaseBucketCache
--------------------

The ``CouchbaseBucketCache`` driver uses Couchbase to store the cache data.

.. code-block:: php
    $bucketName = 'bucket-name';

    $authenticator = new Couchbase\PasswordAuthenticator();
    $authenticator->username('username')->password('password');

    $cluster = new CouchbaseCluster('couchbase://127.0.0.1');

    $cluster->authenticate($authenticator);
    $bucket = $cluster->openBucket($bucketName);

    $cache = new CouchbaseBucketCache($bucket);

FilesystemCache
---------------

The ``FilesystemCache`` driver stores the cache data on the local filesystem.

.. code-block:: php
    $cache = new FilesystemCache('/path/to/cache/directory');

MemecacheCache
--------------

The ``MemcacheCache`` drivers stores the cache data in Memcache.

.. code-block:: php
    $memcache = new Memcache();
    $memcache->connect('localhost', 11211);

    $cache = new MemcacheCache();
    $cache->setMemcache($memcache);

MemcachedCache
--------------

The ``MemcachedCache`` drivers stores the cache data in Memcached.

.. code-block:: php
    $memcached = new Memcached();

    $cache = new MemcachedCache();
    $cache->setMemcached($memcached);

MongoDBCache
------------

The ``MongoDBCache`` drivers stores the cache data in a MongoDB col