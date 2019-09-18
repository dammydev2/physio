$arguments)
    {
        list($prefix, $arguments) = $this->filterPrefix($arguments);

        return $this->invokePluginOnFilesystem($method, $arguments, $prefix);
    }

    /**
     * @param string $from
     * @param string $to
     * @param array  $config
     *
     * @throws InvalidArgumentException
     * @throws FilesystemNotFoundException
     * @throws FileExistsException
     *
     * @return bool
     */
    public function copy($from, $to, array $config = [])
    {
        list($prefixFrom, $from) = $this->getPrefixAndPath($from);

        $buffer = $this->getFilesystem($prefixFrom)->readStream($from);

        if ($buffer === false) {
            return false;
        }

        list($prefixTo, $to) = $this->getPrefixAndPath($to);

        $result = $this->getFilesystem($prefixTo)->writeStream($to, $buffer, $config);

        if (is_resource($buffer)) {
            fclose($buffer);
        }

        return $result;
    }

    /**
     * List with plugin adapter.
     *
     * @param array  $keys
     * @param string $directory
     * @param bool   $recursive
     *
     * @throws InvalidArgumentException
     * @throws FilesystemNotFoundException
     *
     * @return array
     */
    public function listWith(array $keys = [], $directory = '', $recursive = false)
    {
        list($prefix, $directory) = $this->getPrefixAndPath($directory);
        $arguments = [$keys, $directory, $recursive];

        return $this->invokePluginOnFilesystem('listWith', $arguments, $prefix);
    }

    /**
     * Move a file.
     *
     * @param string $from
     * @param string $to
     * @param array  $config
     *
     * @throws InvalidArgumentException
     * @throws FilesystemNotFoundException
     *
     * @return bool
     */
    public function move($from, $to, array $config = [])
    {
        list($prefixFrom, $pathFrom) = $this->getPrefixAndPath($from);
        list($prefixTo, $pathTo) = $this->getPrefixAndPath($to);

        if ($prefixFrom === $prefixTo) {
            $filesystem = $this->getFilesystem($prefixFrom);
            $renamed = $filesystem->rename($pathFrom, $pathTo);

            if ($renamed && isset($config['visibility'])) {
                return $filesystem->setVisibility($pathTo, $config['visibility']);
            }

            return $renamed;
        }

        $copied = $this->copy($from, $to, $config);

        if ($copied) {
            return $this->delete($from);
        }

        return false;
    }

    /**
     * Invoke a plugin on a filesystem mounted on a given prefix.
     *
     * @param string $method
     * @param array  $arguments
     * @param string $prefix
     *
     * @throws FilesystemNotFoundException
     *
     * @return mixed
     */
    public function invokePluginOnFilesystem($method, $arguments, $prefix)
    {
        $filesystem = $this->getFilesystem($prefix);

        try {
            return $this->invokePlugin($method, $arguments, $filesystem);
        } catch (PluginNotFoundException $e) {
            // Let it pass, it's ok, don't panic.
        }

        $callback = [$filesystem, $method];

        return call_user_func_array($callback, $arguments);
    }

    /**
     * @param string $path
     *
     * @throws InvalidArgumentException
     *
     * @return string[] [:prefix, :path]
     */
    protected function getPrefixAndPath($path)
    {
        if (strpos($path, '://') < 1) {
            throw new InvalidArgumentException('No prefix detected in path: ' . $path);
        }

        return explode('://', $path, 2);
    }

    /**
     * Check whether a file exists.
     *
     * @param string $path
     *
     * @return bool
     */
    public function has($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);

        return $this->getFilesystem($prefix)->has($path);
    }

    /**
     * Read a file.
     *
     * @param string $path The path to the file.
     *
     * @throws FileNotFoundException
     *
     * @return string|false The file contents or false on failure.
     */
    public function read($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);

        return $this->getFilesystem($prefix)->read($path);
    }

    /**
     * Retrieves a read-stream for a path.
     *
     * @param string $path The path to the file.
     *
     * @throws FileNotFoundException
     *
     * @return resource|false The path resource or false on failure.
     */
    public function readStream($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);

        return $this->getFilesystem($prefix)->readStream($path);
    }

    /**
     * Get a file's metadata.
     *
     * @param string $path The path to the file.
     *
     * @throws FileNotFoundException
     *
     * @return array|false The file metadata or false on failure.
     */
    public function getMetadata($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);

        return $this->getFilesystem($prefix)->getMetadata($path);
    }

    /**
     * Get a file's size.
     *
     * @param string $path The path to the file.
     *
  