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

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

/**
 * Stores to PHP error_log() handler.
 *
 * @author Elan Ruusamäe <glen@delfi.ee>
 */
class ErrorLogHandler extends AbstractProcessingHandler
{
    const OPERATING_SYSTEM = 0;
    const SAPI = 4;

    protected $messageType;
    protected $expandNewlines;

    /**
     * @param int  $messageType    Says where the error should go.
     * @param int  $level          The minimum logging level at which this handler will be triggered
     * @param bool $bubble         Whether the messages that are handled can bubble up the stack or not
     * @param bool $expandNewlines If set to true, newlines in t