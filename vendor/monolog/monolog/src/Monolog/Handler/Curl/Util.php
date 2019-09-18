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
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\WebProcessor;

class AbstractHandlerTest extends TestCase
{
    /**
     * @covers Monolog\Handler\AbstractHandler::__construct
     * @covers Monolog\Handler\AbstractHandler::getLevel
     * @covers Monolog\Handler\AbstractHandler::setLevel
     * @covers Monolog\Handler\AbstractHandler::getBubble
     * @covers Monolog\Handler\AbstractHandler::setBubble
     * @covers Monolog\Handler\AbstractHandler::getFormatter
     * @covers Monolog\Handler\AbstractHandler::setFormatter
     */
    public function testConstructAndGetSet()
    {
        $handler = $this->getMockForAbstractClass('Monolog\Handler\AbstractHandler', array(Logger::WARNING, false));
        $this->assertEquals(Logger::WARNING, $handler->getLevel());
        $this->assertEquals(false, $handler->getBubble());

        $handler->setLevel(Logger::ERROR);
        $handler->setBubble(true);
        $handler->setFormatter($formatter = new LineFormatter);
        $this->assertEquals(Logger::ERROR, $handler->getLevel());
        $this->assertEquals(true, $handler->getBubble());
        $this->assertSame($formatter, $handler->getFormatter());
    }

    /**
  