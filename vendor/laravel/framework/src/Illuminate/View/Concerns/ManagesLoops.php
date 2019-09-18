nction update($path, $contents, array $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);

        return $this->getFilesystem($prefix)->update($path, $contents, $config);
    }

    /**
     * Update an existing file using a stream.
     *
     * @param string   $path     The path of the existing file.
     * @param resource $resource The file handle.
     * @param array    $config   An optional configuration array.
     *
     * @throws InvalidArgumentException If $resource is not a file handle.
     * @throws FileNotFoundException
     *
     * @return bool True on success, false on failure.
     */
    public function updateStream($path, $resource, array $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);

        return $this->getFilesystem($prefix)->updateStream($path, $resource, $config);
    }

    /**
     * Rename a file.
     *
     * @param string $path    Path to the existing file.
     * @param string $newpath The new path of the file.
     *
     * @throws FileExistsException   Thrown if $newpath exists.
     * @throws FileNotFoundException Thrown if $path does not exist.
     *
     * @return bool True on success, false on failure.
     */
    public function rename($path, $newpath)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);

        return $this->getFilesystem($prefix)->rename($path, $newpath);
    }

    /**
     * Delete a file.
     *
     * @param string $path
     *
     * @throws FileNotFoundException
     *
     * @return bool True on success, false on failure.
     */
    public function delete($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);

        return $this->getFilesystem($prefix)->delete($path);
    }

    /**
     * Delete a directory.
     *
     * @param string $dirname
     *
     * @throws RootViolationException Thrown if $dirname is empty.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteDir($dirname)
    {
        list($prefix, $dirname) = $this->getPrefixAndPath($dirname);

        return $this->getFilesystem($prefix)->deleteDir($dirname);
    }

    /**
     * Create a directory.
     *
     * @param 