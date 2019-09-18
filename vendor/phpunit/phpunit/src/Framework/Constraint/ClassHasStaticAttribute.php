<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\MockObject;

use PHPUnit\Framework\TestCase;

/**
 * Implementation of the Builder pattern for Mock objects.
 */
class MockBuilder
{
    /**
     * @var TestCase
     */
    private $testCase;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $methods = [];

    /**
     * @var array
     */
    private $methodsExcept = [];

    /**
     * @var string
     */
    private $mockClassName = '';

    /**
     * @var array
     */
    private $constructorArgs = [];

    /**
     * @var bool
     */
    private $originalConstructor = true;

    /**
     * @var bool
     */
    private $originalClone = true;

    /**
     * @var bool
     */
    private $autoload = true;

    /**
     * @var bool
     */
    private $cloneArguments = false;

    /**
     * @var bool
     */
    private $callOriginalMethods = false;

    /**
     * @var object
     */
    private $proxyTarget;

    /**
     * @var bool
     */
    private $allowMockingUnk