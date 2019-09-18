<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Monolog;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Monolog\Handler\AbstractHandler;
use Monolog\Registry;

/**
 * Monolog error handler
 *
 * A facility to enable logging of runtime errors, exceptions and fatal errors.
 *
 * Quick setup: <code>ErrorHandler::register($logger);</code>
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class ErrorHandler
{
    private $logger;

    private $previousExceptionHandler;
    private $uncaughtExceptionLevel;

    private $previousErrorHandler;
    private $errorLevelMap;
    private $handleOnlyReportedErrors;

    private $hasFatalErrorHandler;
    private 