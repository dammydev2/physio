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
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mockery/blob/master/LICENSE New BSD License
 */

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;

class Mockery_MockTest extends MockeryTestCase
{
    public function testAnonymousMockWorksWithNotAllowingMockingOfNonExistentMethods()
    {
        \Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
        $m = mock();
        $m->shouldReceive("test123")->andReturn(true);
        assertThat($m->test123(), equalTo(true));
        \Mockery::getConfiguration()->allowMockingNonExistentMethods(true);
    }

    public function testMockWithNotAllowingMockingOfNonExistentMethodsCanBeGivenAdditionalMethodsToMockEvenIfTheyDontExistOnClass()
    {
        \Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
        $m = mock('ExampleClassForTestingNonExistentMethod');
        $m->shouldAllowMockingMethod('testSomeNonExistentMethod');
        $m->shouldReceive("testSomeNonExistentMethod")->andReturn(true);
        assertThat($m->testSomeNonExistentMethod(), equalTo(true));
        \Mockery::getConfiguration()->allowMockingNonExistentMethods(true);
    }

    public function testProtectedMethodMockWithNotAllowingMockingOfNonExistentMethodsWhenShouldAllowMockingProtectedMethodsIsCalled()
    {
        \Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
        $m = mock('ClassWithProtectedMethod');
        $m->shouldAllowMockingProte