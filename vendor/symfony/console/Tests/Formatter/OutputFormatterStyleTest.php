<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Tests\Input;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class InputDefinitionTest extends TestCase
{
    protected static $fixtures;

    protected $foo;
    protected $bar;
    protected $foo1;
    protected $foo2;

    public static function setUpBeforeClass()
    {
        self::$fixtures = __DIR__.'/../Fixtures/';
    }

    public function testConstructorArguments()
    {
        $this->initializeArguments();

        $definition = new InputDefinition();
        $this->assertEquals([], $definition->getArguments(), '__construct() creates a new InputDefinition object');

        $definition = new InputDefinition([$this->foo, $this->bar]);
        $this->assertEquals(['foo' => $this->foo, 'bar' => $this->bar], $definition->getArguments(), '__construct() takes an array of InputArgument objects as its first argument');
    }

    public function testConstructorOptions()
    {
        $this->initializeOptions();

        $definition = new InputDefinition();
        $this->assertEquals([], $definition->getOptions(), '__construct() creates a new InputDefinition object');

        $definition = new InputDefinition([$this->foo, $this->bar]);
        $this->assertEquals(['foo' => $this->foo, 'bar' => $this->bar], $definition->getOptions(), '__construct() takes an array of InputOption objects as its first argument');
    }

    public function testSetArguments()
    {
        $this->initializeArguments();

        $definition = new InputDefinition();
        $definition->setArguments([$this->foo]);
        $this->assertEquals(['foo' => $this->foo], $definition->getArguments(), '->setArguments() sets the array of InputArgument objects');
        $definition->setArguments([$this->bar]);

        $this->assertEquals(['bar' => $this->bar], $definition->getArguments(), '->setArguments() clears all InputArgument objects');
    }

    public function testAddArguments()
    {
        $this->initializeArguments();

        $definition = new InputDefinition();
        $definition->addArguments([$this->foo]);
        $this->assertEquals(['foo' => $this->foo], $definition->getArguments(), '->addArguments() adds an array of InputArgument objects');
        $definition->addArguments([$this->bar]);
        $this->assertEquals(['foo' => $this->foo, 'bar' => $this->bar], $definition->getArguments(), '->addArguments() does not clear existing InputArgument objects');
    }

    public function testAddArgument()
    {
        $this->initializeArguments();

        $definition = new InputDefinition();
        $definition->addArgument($this->foo);
        $this->assertEquals(['foo' => $this->foo], $definition->getArguments(), '->addArgument() adds a InputArgument object');
        $definition->addArgument($this->bar);
        $this->assertEquals(['foo' => $this->foo, 'bar' => $this->bar], $definition->getArguments(), '->addArgument() adds a InputArgument object');
    }

    /**
     * @expectedException        \LogicException
     * @expectedExceptionMessage An argument with name "foo" already exists.
     */
    public function testArgumentsMustHaveDifferentNames()
    {
        $this->initializeArguments();

        $definition = new InputDefinition();
        $definition->addArgument($this->foo);
        $definition->addArgument($this->foo1);
    }

    /**
     * @expectedException        \LogicException
     * @expectedExceptionMessage Cannot add an argument after an array argument.
     */
    public function testArrayArgumentHasToBeLast()
    {
        $this->initializeArguments();

        $definition = new InputDefinition();
        $definition->addArgument(new InputArgument('fooarray', InputArgument::IS_ARRAY));
 