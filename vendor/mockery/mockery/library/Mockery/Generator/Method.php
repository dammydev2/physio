<?php
/**
 * Mockery
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://github.com/padraic/mockery/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to padraic@php.net so we can send you a copy immediately.
 *
 * @category   Mockery
 * @package    Mockery
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mockery/blob/master/LICENSE New BSD License
 */

use Mockery\Generator\MockConfigurationBuilder;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use Mockery\Exception\BadMethodCallException;

class ContainerTest extends MockeryTestCase
{
    public function testSimplestMockCreation()
    {
        $m = mock();
        $m->shouldReceive('foo')->andReturn('bar');
        $this->assertEquals('bar', $m->foo());
    }

    public function testGetKeyOfDemeterMockShouldReturnKeyWhenMatchingMock()
    {
        $m = mock();
        $m->shouldReceive('foo->bar');
        $this->assertRegExp(
            '/Mockery_(\d+)__demeter_([0-9a-f]+)_foo/',
            Mockery::getContainer()->getKeyOfDemeterMockFor('foo', get_class($m))
        );
    }
    public function testGetKeyOfDemeterMockShouldReturnNullWhenNoMatchingMock()
    {
        $method = 'unknownMethod';
        $this->assertNull(Mockery::getContainer()->getKeyOfDemeterMockFor($method, 'any'));

        $m = mock();
        $m->shouldReceive('method');
        $this->assertNull(Mockery::getContainer()->getKeyOfDemeterMockFor($method, get_class($m)));

        $m->shouldReceive('foo->bar');
        $this->assertNull(Mockery::getContainer()->getKeyOfDemeterMockFor($method, get_class($m)));
    }


    public function testNamedMocksAddNameToExceptions()
    {
        $m = mock('Foo');
        $m->shouldReceive('foo')->with(1)->andReturn('bar');
        try {
            $m->foo();
        } catch (\Mockery\Exception $e) {
            $this->assertTrue((bool) preg_match("/Foo/", $e->getMessage()));
        }
    }

    public function testSimpleMockWithArrayDefs()
    {
        $m = mock(array('foo'=>1, 'bar'=>2));
        $this->assertEquals(1, $m->foo());
        $thi