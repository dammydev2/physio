<?php

namespace League\Flysystem;

use InvalidArgumentException;

interface FilesystemInterface
{
    /**
     * Check whether a file exists.
     *
     * @param string $path
     *
     * @return bool
     */
    public function has($path);

    /**
     * Read a file.
     *
     * @param string $path The path to the file.
     *
     * @throws FileNotFoundException
     *
     * @return string|false The file contents or false on failure.
     */
    public function read($path);

    /**
     * Retrieves a read-stream for a path.
     *
     * @param string $path The path to the file.
     *
     * @throws FileNotFoundException
     *
     * @return resource|false The path resource or false on failure.
     */
    public function readStream($path);

    /**
     * List contents of a directory.
     *
     * @param string $directory The directory to list.
     * @param bool   $recursive Whether to list recursively.
     *
     * @return array A list of file metadata.
     */
    public function listContents($directory = '', $recursive = 