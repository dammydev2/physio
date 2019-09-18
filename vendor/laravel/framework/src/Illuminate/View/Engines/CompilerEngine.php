<?php

namespace League\Flysystem;

use League\Flysystem\Util\MimeType;
use LogicException;

class Util
{
    /**
     * Get normalized pathinfo.
     *
     * @param string $path
     *
     * @return array pathinfo
     */
    public static function pathinfo($path)
    {
        $pathinfo = compact('path');

        if ('' !== $dirname = dirname($path)) {
            $pathinfo['dirname'] = static::normalizeDirname($dirname);
        }

        $pathinfo['basename'] = static::basename($path);

        $pathinfo += pathinfo($pathinfo['basename']);

        return $pathinfo + ['dirname' => ''];
    }

    /**
     * Normalize a dirname return value.
     *
     * @param string $dirname
     *
     * @return string normalized dirname
     */
    public static function normalizeDirname($dirname)
    {
        return $dirname === '.' ? '' : $dirname;
    }

    /**
     * Get a normalized dirname from a path.
     *
     * @param string $path
     *
     * @return string dirname
     */
    public static function dirname($path)
    {
        return static::normalizeDirname(dirname($path));
    }

    /**
     * Map result arrays.
     *
     * @param array $object
     * @param array $map
     *
     * @return array mapped result
     */
    public static function map(array $object, array $map)
    {
        $result = [];

        foreach ($map as $from => $to) {
            if ( ! isset($object[$from])) {
                continue;
            }

            $result[$to] = $object[$from];
        }

        return $result;
    }

    /**
     * Normalize path.
     *
     * @param string $path
     *
     * @throws LogicException
     *
     * @return string
     */
    public static function normalizePath($path)
    {
        return static::normalizeRelativePath($path);
    }

    /**
     * Normalize relative directories in a path.
     *
     * @param string $path
     *
     * @throws LogicException
     *
     * @return string
     */
    public static function normalizeRelativePath($path)
    {
        $path = str_replace('\\', '/', $path);
        $path = static::removeFunkyWhiteSpace($path);

        $parts = [];

        foreach (explode('/', $path) as $part) {
            switch ($part) {
                case '':
                case '.':
                break;

            case '..':
                if (empty($parts)) {
                    throw new LogicException(
                        'Path is outside of the defined root, path: [' . $path . ']'
                    );
                }
                array_pop($parts);
                break;

            default:
   