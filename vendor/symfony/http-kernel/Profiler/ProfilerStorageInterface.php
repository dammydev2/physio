<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Tests\Debug;

use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Stopwatch\Stopwatch;

class TraceableEventDispatcherTest extends TestCase
{
    public function testStopwatchSections()
    {
        $dispatcher = new TraceableEventDispatcher(new EventDispatcher(), $stopwatch = new Stopwatch());
        $kernel = $this->getHttpKernel($dispatcher, function () { return new Response(); });
        $request = Request::create('/');
        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);

        $events = $stopwatch->getSectionEvents($response->headers->get('X-Debug-Token'));
        $this->assertEquals([
            '__section__',
            'kernel.request',
            'kernel.controller',
            'kernel.controller_arguments',
            'controller',
            'kernel.response',
            'kernel.terminate',
        ], array_keys($events));
    }

    public function testStopwatchCheckControllerOnRequestEvent()
    {
        $stopwatch = $this->getMockBuilder('Symfony\Component\Stopwatch\Stopwatch')
            ->setMethods(['isStarted'])
            ->getMock();
        $stopwatch->expects($this->on