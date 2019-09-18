<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Debug\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use Symfony\Component\Debug\BufferingLogger;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\Exception\SilencedErrorContext;
use Symfony\Component\Debug\Tests\Fixtures\ErrorHandlerThatUsesThePreviousOne;
use Symfony\Component\Debug\Tests\Fixtures\LoggerThatSetAnErrorHandler;

/**
 * ErrorHandlerTest.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ErrorHandlerTest extends TestCase
{
    public function testRegister()
    {
        $handler = ErrorHandler::register();

        try {
            $this->assertInstanceOf('Symfony\Component\Debug\ErrorHandler', $handler);
            $this->assertSame($handler, ErrorHandler::register());

            $newHandler = new ErrorHandler();

            $this->assertSame($handler, ErrorHandler::register($newHandler, false));
            $h = set_error_handler('var_dump');
            restore_error_handler();
            $this->assertSame([$handler, 'handleError'], $h);

            try {
                $this->assertSame($newHandler, ErrorHandler::register($newHandler, true));
                $h = set_error_handler('var_dump');
                restore_error_handler();
                $this->assertSame([$newHandler, 'handleError'], $h);
            } catch (\Exception $e) {
            }

            restore_error_handler();
            restore_exception_handler();

            if (isset($e)) {
                throw $e;
            }
        } catch (\Exception $e) {
        }

        restore_error_handler();
        restore_exception_handler();

        if (isset($e)) {
            throw $e;
        }
    }

    public function testErrorGetLast()
    {
        $handler = ErrorHandler::register();
        $logger = $this->getMockBuilder('Psr\Log\LoggerInterface')->getMock();
        $handler->setDefaultLogger($logger);
        $handler->screamAt(E_ALL);

        try {
            @trigger_error('Hello', E_USER_WARNING);
            $expected = [
                't