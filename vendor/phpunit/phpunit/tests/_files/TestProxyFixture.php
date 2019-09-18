<?php

namespace Prettus\Repository\Generators;

/**
 * Class Stub
 * @package Prettus\Repository\Generators
 */
class Stub
{
    /**
     * The base path of stub file.
     *
     * @var null|string
     */
    protected static $basePath = null;
    /**
     * The stub path.
     *
     * @var string
     */
    protected $path;
    /**
     * The replacements array.
     *
     * @var array
     */
    protected $replaces = [];

    /**
     * The contructor.
     *
     * @param string $path
     * @param array  $replaces
     */
    public function __construct($path, array $replaces = [])
    {
        $this->path = $path;
        $this->replaces = $replaces;
    }

    /**
     * Create new self instance.
     *
     * @param  string $path
     * @param  array  $replaces
     *
     * @return self
     */
    public static function create($path, array $replaces = [])
    {
        return new static($path, $replaces);
    }
