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

class WhatFailureGroupHandlerTest extends TestCase
{
    /**
     * @covers Monolog\Handler\WhatFailureGroupHandler::__construct
     * @expectedException InvalidArgumentException
     */
    public function testConstructorOnlyTakesHandler()
    {
        new WhatFailureGroupHandler(array(new TestHandler(), "foo"));
    }

    /**
     * @covers Monolog\Handler\WhatFailureGroupHandler::__construct
     * @covers Monolog\Handler\WhatFailureGroupHandler::handle
     */
    public function testHandle()
    {
        $testHandlers = array(new TestHandler(), new TestHandler());
        $handler = new WhatFailureGroupHandler($testHandlers);
        $handler->handle($this->getRecord(Logger::DEBUG));
        $handler->handle($this->getRecord(Logger::INFO));
        foreach ($testHandlers as $test) {
            $this->assertTrue($test->hasDebugRecords());
            $this->assertTrue($test->hasInfoRecords());
            $this->assertTrue(count($test->getRecords()) === 2);
        }
    }

    /**
     * @covers Monolog\Handler\WhatFailureGroupHandler::handleBatch
     */
    public function testHandleBatch()
    {
        $testHandlers = array(new TestHandler