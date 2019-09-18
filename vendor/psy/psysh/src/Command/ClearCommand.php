<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\ExecutionLoop;

use Psy\Context;
use Psy\Exception\BreakException;
use Psy\Shell;

/**
 * An execution loop listener that forks the process before executing code.
 *
 * This is awesome, as the session won't die prematurely if user input includes
 * a fatal error, such as redeclaring a class or function.
 */
class ProcessForker extends AbstractListener
{
    private $savegame;
    private $up;

    /**
     * Process forker is supported if pcntl and posix extensions are available.
     *
     * @return bool
     */
    public static function isSupported()
    {
        return \function_exists('pcntl_signal') && \function_exists('posix_getpid');
    }

    /**
     * Forks into a master and a loop process.
     *
     * The loop process will handle the evaluation of all instructions, then
     * return