<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Test;

use Psy\Context;

class ContextTest extends \PHPUnit\Framework\TestCase
{
    public function testGet()
    {
        $this->assertTrue(true);
    }

    public function testGetAll()
    {
        $this->assertTrue(true);
    }

    public function testGetSpecialVariables()
    {
        $context = new Context();

        $this->assertNull($context->get('_'));
        $this->assertNull($context->getReturnValue());

        $this->assertEquals(['_' => null], $context->getAll());

        $e = new \Exception('eeeeeee');
        $obj = new \StdClass();
        $context->setLastException($e);
        $context->setLastStdout('out');
        $context->setBoundObject($obj);

        $context->setCommandScopeVariables([
            '__function'  => 'function',
            '__method'    => 'method',
            '__class'     => 'class',
            '__namespace' => 'namespace',
            '__file'      => 'file',
            '__line'      => 'line',
            '__dir'       => 'dir',
        ]);

        $expected = [
            '_'           => null,
            '_e'          => $e,
            '__out'       => 'out',
            'this'        => $obj,
            '__function'  => 'function',
            '__method'    => 'method',
            '__class'     => 'class',
            '__namespace' => 'namespace',
            '__file'      => 'file',
            '__line'      => 'line',
            '__dir'       => 'dir',
        ];

        $this->assertEquals($expected, $context->getAll());
    }

    public function testSetAll()
    {
        $context = new Context();

        $baz = new \StdClass();
        $vars = [
            'foo' => 'Foo',
            'bar' => 123,
            'baz' => $baz,

            '_'         => 'fail',
            '_e'        => 'fail',
            '__out'     => 'fail',
            'this'      => 'fail',
            '__psysh__' => 'fail',

            '__function'  => 'fail',
            '__method'    => 'fail',
            '__class'     => 'fail',
            '__namespace' => 'fail',
            '__file'      => 'fail',
            '__line'      => 'fail',
            '__dir'       => 'fail',
        ];

        $context->setAll($vars);

        $this->assertEquals('Foo', $context->get('foo'));
        $this->assertEquals(123, $context->get('bar'));
        $this->assertSame($baz, $context->get('baz'));

        $this->assertEquals(['foo' => 'Foo', 'bar' => 123, 'baz' => $baz, '_' => null], $context->getAll());
    }

    /**
     * @dataProvider specialNames
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegEx /Unknown variable: \$\w+/
     */
    public function testSetAllDoesNotSetSpecial($name)
    {
        $context = new Context();
        $context->setAll([$name => 'fail']);
        $context->get($name);
    }

    public function specialNames()
    {
        return [
            ['_e'],
            ['__out'],
            ['this'],
            ['__psysh__'],
            ['__function'],
            ['__method'],
            ['__class'],
            ['__namespace'],
            ['__file'],
            ['__line'],
            ['__dir'],
        ];
    }

    public function testReturnValue()
    {
        $context = new Context();
        $this->assertNull($context->getReturnValue());

        $val = 'some string';
        $context->setReturnValue($val);
        $this->assertEquals($val, $context->getReturnValue());
        $this->assertEquals($val, $context->get('_'));

        $obj = new \StdClass();
        $context->setReturnValue($obj);
        $this->assertSame($obj, $context->getReturnValue());
        $this->assertSam