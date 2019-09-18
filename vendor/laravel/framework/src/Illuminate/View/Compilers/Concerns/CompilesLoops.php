<?php

namespace League\Flysystem;

use InvalidArgumentException;
use League\Flysystem\Adapter\CanOverwriteFiles;
use League\Flysystem\Plugin\PluggableTrait;
use League\Flysystem\Util\ContentListingFormatter;

/**
 * @method array getWithMetadata(string $path, array $metadata)
 * @method bool  forceCopy(string $path, string $newpath)
 * @method bool  forceRename(string $path, string $newpath)
 * @method array listFiles(string $path = '', boolean $recursive = false)
 * @method array listPaths(string $path = '', boolean $recursive = false)
 * @method array listWith(array $keys = [], $directory = '', $recursive = false)
 */
class Filesystem implements FilesystemInterface
{
    use PluggableTrait;
    use ConfigAwareTrait;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Constructor.
     *
     * @param AdapterInterface $adapter
     * @param Config|array     $config
     */
    public function __construct(AdapterInterface $adapter, $config = null)
    {
        $this->adapter = $adapter;
        $this->setConfig($config);
    }

    /**
     * Get the Adapter.
     *
     * @return AdapterInterface adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @inheritdoc
     */
    public function has($path)
    {
        $path = Util::normalizePath($path);

        return strlen($path) === 0 ? false : (bool) $this->getAdapter()->has($path);
    }

    /**
     * @inheritdoc
     */
    public function write($path, $contents, array $config = [])
    {
        $path = Util::normalizePath($path);
        $this->assertAbsent($path);
        $config = $this->prepareConfig($config);

        return (bool) $this->getAdapter()->write($path, $contents, $config);
    }

    /**
     * @inheritdoc
     */
    public function writeStream($path, $resource, array $config = [])
    {
        if ( ! is_resource($resource)) {
            throw new InvalidArgumentException(__METHOD__ . ' expects argument #2 to be a valid resource.');
        }

        $path = Util::normalizePath($path);
        $this->assertAbsent($path);
        $config = $this->prepareConfig($config);

        Util::rewindStream($resource);

        return (bool) $this->getAdapter()->writeStream($path, $resource, $config);
    }

    /**
     * @inheritdoc
     */
    public function put($path, $contents, array $config = [])
    {
        $path = Util::normalizePath($path);
        $config = $this->prepareConfig($config);

        if ( ! $this->getAdapter() instanceof CanOverwriteFiles && $this->has($path)) {
            return (bool) $this->getAdapter()->update($path, $contents, $config);
        }

        return (bool) $this->getAdapter()->write($path, $contents, $config);
    }

    /**
     * @inheritdoc
     */
    public function putStream($path, $resource, array $config = [])
    {
        if ( ! is_resource($resource)) {
            throw new InvalidArgumentException(__METHOD__ . ' expects argument #2 to be a valid resource.');
        }

        $path = Util::normalizePath($path);
        $config = $this->prepareConfig($config);
        Util::rewindStream($resource);

        if ( ! $this->getAdapter() instanceof CanOverwriteFiles &&$this->has($path)) {
            return (bool) $this->getAdapter()->updateStream($path, $resource, $config);
        }

        return (bool) $this->getAdapter()->writeStream($path, $resource, $config);
    }

    /**
     * @inheritdoc
     */
    public function readAndDelete($path)
    {
        $path = Util::normalizePath($path);
        $this->assertPresent($path);
        $contents = $this->read($path);

        if ($contents === false) {
            return false;
        }

        $this->delete($path);

        return $contents;
    }

    /**
     * @inheritdoc
     */
    public function update($path, $contents, array $config = [])
    {
        $path = Util::normalizePath($path);
        $config = $this->prepareConfig($config);

        $this->assertPresent($path);

        return (bool) $this->getAdapter()->update($path, $contents, $config);
    }

    /**
     * @inheritdoc
     */
    public function updateStream($path, $resource, array $config = [])
    {
        if ( ! is_resource($resource)) {
            throw new InvalidArgumentException(__METHOD__ . ' expects argument #2 to be a valid resource.');
        }

        $path = Util::normalizePath($path);
        $config = $th