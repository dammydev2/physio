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
use ReflectionExtension;

/**
 * Monolog POSIX signal handler
 *
 * @author Robert Gust-Bardon <robert@gust-bardon.org>
 */
class SignalHandler
{
    private $logger;

    private $previousSignalHandler = array();
    private $signalLevelMap = array();
    private $signalRestartSyscalls = array();

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function registerSignalHandler($signo, $level = LogLevel::CRITICAL, $callPrevious = true, $restartSyscalls = true, $async = true)
    {
        if (!extension_loaded('pcn