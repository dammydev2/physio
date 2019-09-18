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

function error_log()
{
    $GLOBALS['error_log'][] = func_get_args();
}

class ErrorLogHandlerTest extends TestCase
{
    protected function setUp()
    {
        $GLOBALS['error_log'] = array();
    }

    /**
     * @covers Monolog\Handler\ErrorLogHandler::__construct
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The given message type "42" is not supported
     */
    public function testShouldNotAcceptAnInvalidTypeOnContructor()
    {
        new ErrorLogHandler(42);
    }

    /**
     * @covers Monolog\Handler\ErrorLogHandler::write
     */