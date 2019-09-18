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

if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
    require_once __DIR__.'/DummyClasses/DemeterChain.php';
}

use Mockery\Adapter\Phpunit\MockeryTestCase;

class DemeterChainTest extends MockeryTestCase
{
    /** @var  Mockery\Mock $this->mock */
    private $mock;

    public function mockeryTestSetUp()
    {
        $this->mock = $this->mock = Mockery::mock()->shouldIgnoreMissing();
    }

    public function mockeryTestTearDown()
    {
        $this->mock->mockery_getContainer()->mockery_close();
    }

    public function testTwoChains()
    {
        $this->mock->shouldReceive('getElement->getFirst')
            ->once()
            ->andReturn('something');

        $this->mock->shouldReceive('getElement->getSecond')
            ->once()
            ->andReturn('somethingElse');

        $this->assertEquals(
            'something',
            $this->mock->getElement()->getFirst()
        );
        $this->assertEquals(
            'somethingElse',
            $this->mock->getElement()->getSecond()
        );
        $this->mock->mockery_getContainer()->mockery_close();
    }

    public function testTwoChainsWithExpectedParameters()
    {
        $thi