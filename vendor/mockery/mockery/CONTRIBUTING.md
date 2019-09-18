<?php
/**
 * Mockery
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://github.com/padraic/mockery/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to padraic@php.net so we can send you a copy immediately.
 *
 * @category   Mockery
 * @package    Mockery
 * @copyright  Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mockery/blob/master/LICENSE New BSD License
 */

use Mockery\ExpectationInterface;
use Mockery\Generator\CachingGenerator;
use Mockery\Generator\Generator;
use Mockery\Generator\MockConfigurationBuilder;
use Mockery\Generator\StringManipulationGenerator;
use Mockery\Loader\EvalLoader;
use Mockery\Loader\Loader;
use Mockery\Matcher\MatcherAbstract;
use Mockery\ClosureWrapper;

class Mockery
{
    const BLOCKS = 'Mockery_Forward_Blocks';

    /**
     * Global container to hold all mocks for the current unit test running.
     *
     * @var \Mockery\Container|null
     */
    protected static $_container = null;

    /**
     * Global configuration handler containing configuration options.
     *
     * @var \Mockery\Configuration
     */
    protected static $_config = null;

    /**
     * @var \Mockery\Generator\Generator
     */
    protected static $_generator;

    /**
     * @var \Mockery\Loader\Loader
     */
    protected static $_loader;

    /**
     * @var array
     */
    private static $_filesToCleanUp = [];

    /**
     * Defines the global helper functions
     *
     * @return void
     */
    public static function globalHelpers()
    {
        require_once __DIR__.'/helpers.php';
    }

    /**
     * @return array
     */
    public static function builtInTypes()
    {
        $builtInTypes = array(
            'self',
            'array',
            'callable',
            // Up to php 7
            'bool',
            'float',
            'int',
            'string',
            'iterable',
            'void',
        );

        if (version_compare(PHP_VERSION, '7.2.0-dev') >= 0) {
            $builtInTypes[] = 'object';
        }

        return $builtInTypes;
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isBuiltInType($type)
    {
        return in_array($type, \Mockery::builtInTypes());
    }

    /**
     * Static shortcut to \Mockery\Container::mock().
     *
     * @param mixed ...$args
     *
     * @return \Mockery\MockInterface
     */
    public static function mock(...$args)
    {
        return call_user_func_array(array(self::getContainer(), 'mock'), $args);
    }

    /**
     * Static and semantic shortcut for getting a mock from the container
     * and applying the spy's expected behavior into it.
     *
     * @param mixed ...$args
     *
     * @return \Mockery\MockInterface
     */
    public static function spy(...$args)
    {
        if (count($args) && $args[0] instanceof \Closure) {
            $args[0] = new ClosureWrapper($args[0]);
        }

        return call_user_func_array(array(self::getCont