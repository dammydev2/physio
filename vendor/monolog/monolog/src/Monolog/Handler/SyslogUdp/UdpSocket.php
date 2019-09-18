<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Monolog\Handler;

use Monolog\TestCase;
use Monolog\Logger;

class BufferHandlerTest extends TestCase
{
    private $shutdownCheckHandler;

    /**
     * @covers Monolog\Handler\BufferHandler::__construct
     * @covers Monolog\Handler\BufferHandler::handle
     * @covers Monolog\Handler\BufferHandler::close
     */
    public function testHandleBuffers()
    {
        $test = new TestHandler();
        $handler = new BufferHandler($test);
        $handler->handle($this->getRecord(Logger::DEBUG));
        $handler->handle($this->getRecord(Logger::INFO));
        $this->assertFalse($test->hasDebugRecords());
        $this->assertFalse($test->hasInfoRecords());
        $handler->close();
        $this->assertTrue($test->hasInfoRecords());
        $this->assertTrue(count($test->getRecords()) === 2);
    }

    /**
     * @covers Monolog\Handler\BufferHandler::close
     * @covers Monolog\Handler\BufferHandler::flush
     */
    public function testPropagatesRecordsAtEndOfRequest()
    {
        $test = new TestHandler();
        $handler = new BufferHandler($test);
        $handler->handle($this->getRecord(Logger::WARNING));
        $handl