<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Tests\DataCollector;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Debug\Exception\SilencedErrorContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\LoggerDataCollector;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class LoggerDataCollectorTest extends TestCase
{
    public function testCollectWithUnexpectedFormat()
    {
        $logger = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\Log\DebugLoggerInterface')
            ->setMethods(['countErrors', 'getLogs', 'clear'])
            ->getMock();
        $logger->expects($this->once())->method('countErrors')->will($this->returnValue('foo'));
        $logger->expects($this->exactly(2))->method('getLogs')->will($this->returnValue([]));

        $c = new LoggerDataCollector($logger, __DIR__.'/');
        $c->lateCollect();
        $compilerLogs = $c->getCompilerLogs()->getValue('message');

        $this->assertSame([
            ['message' => 'Removed service "Psr\Container\ContainerInterface"; reason: private alias.'],
            ['message' => 'Removed service "Symfony\Component\DependencyInjection\ContainerInterface"; reason: private alias.'],
        ], $compilerLogs['Symfony\Component\DependencyInjection\Compiler\RemovePrivateAliasesPass']);

        $this->assertSame([
            ['message' => 'Some custom logging message'],
            ['message' => 'With ending :'],
        ], $compilerLogs['Unknown Compiler Pass']);
    }

    public function testWithMasterRequest()
    {
        $masterRequest = new Request();
        $stack = new RequestStack();
        $stack->push($masterRequest);

        $logger = $this
            ->getMockBuilder(DebugLoggerInterface::class)
            ->setMethods(['countErrors', 'getLogs', 'clear'])
            ->getMock();
        $logger->expects($this->once())->method('countErrors')->with(null);
        $logger->expects($this->exactly(2))->method('getLogs')->with(null)->will($this->returnValue([]));

        $c = new LoggerDataCollector($logger, __DIR__.'/', $stack);

        $c->collect($masterRequest, new Response());
        $c->lateCollect();
    }

    public function testWithSubRequest()
    {
        $masterRequest = new Request();
        $subRequest = new Request();
