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

namespace test\Mockery;

use Mockery as m;
use Mockery\Spy;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class SpyTest extends MockeryTestCase
{
    /** @test */
    public function itVerifiesAMethodWasCalled()
    {
        $spy = m::spy();
        $spy->myMethod();
        $spy->shouldHaveReceived("myMethod");

        $this->expectException("Mockery\Exception\InvalidCountException");
        $spy->shouldHaveReceived("someMethodThatWasNotCalled");
    }

    /** @test */
    public function itVerifiesAMethodWasNotCalled()
    {
        $spy = m::spy();
        $spy->shouldNotHaveReceived("myMethod");

        $this->expectException("Mockery\Exception\InvalidCountException");
        $spy->myMethod();
        $spy->shouldNotHaveReceived("myMethod");
    }

    /** @test */
    public function itVerifiesAMethodWasNotCalledWithParticularArguments()
    {
        $spy = m::spy();
        $spy->myMethod(123, 456);

        $spy->shouldNotHaveReceived("myMethod", array(789, 10));

        $this->expectException("Mockery\Exception\InvalidCountException");
        $spy->shouldNotHaveReceived("myMethod", array(123, 456));
    }

    /** @test */
    public function itVerifiesAMethodWasCalledASpecificNumberOfTimes()
    {
        $spy = m::spy();
        $spy->myMethod();
        $spy->myMethod();
        $spy->shouldHaveReceived("myMethod")->twice();

       