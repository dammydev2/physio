<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy;

use Psy\VersionUpdater\GitHubChecker;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use XdgBaseDir\Xdg;

if (!\function_exists('Psy\sh')) {
    /**
     * Command to return the eval-able code to startup PsySH.
     *
     *     eval(\Psy\sh());
     *
     * @return string
     */
    function sh()
    {
        return 'extract(\Psy\debug(get_defined_vars(), isset($this) ? $this : @get_called_class()));';
    }
}

if (!\function_exists('Psy\debug')) {
    /**
     * Invoke a Psy Shell from the current context.
     *
     * For example:
     *
     *     