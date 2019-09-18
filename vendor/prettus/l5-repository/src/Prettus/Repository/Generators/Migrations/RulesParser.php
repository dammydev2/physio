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

use Psy\Exception\DeprecatedException;
use Psy\Exception\RuntimeException;
use Psy\Output\OutputPager;
use Psy\Output\ShellOutput;
use Psy\Readline\GNUReadline;
use Psy\Readline\HoaConsole;
use Psy\Readline\Libedit;
use Psy\Readline\Readline;
use Psy\Readline\Transient;
use Psy\TabCompletion\AutoCompleter;
use Psy\VarDumper\Presenter;
use Psy\VersionUpdater\Checker;
use Psy\VersionUpdater\GitHubChecker;
use Psy\VersionUpdater\IntervalChecker;
use Psy\VersionUpdater\NoopChecker;

/**
 * The Psy Shell configuration.
 */
class Configuration
{
    const COLOR_MODE_AUTO     = 'auto';
    const COLOR_MODE_FORCED   = 'forced';
    const COLOR_MODE_DISABLED = 'disabled';

    private static $AVAILABLE_OPTIONS = [
        'codeCleaner',
        'colorMode',
        'configDir',
        'dataDir',
        'defaultIncludes',
        'eraseDuplicates',
        'errorLoggingLevel',
        'forceArrayIndexes',
        'historySize',
        'manualDbFile',
        'pager',
        'prompt',
        'requireSemicolons',
        'runtimeDir',
        'startupMessage',
        'updateCheck',
        'useBracketedPaste',
        'usePcntl',
        'useReadline',
        'useTabCompletion',
        'useUnicode',
        'warnOnMultipleConfigs',
    ];

    private $defaultIncludes;
    private $configDir;
    private $dataDir;
    private $runtimeDir;
    private $configFile;
    /** @var string|false */
    private $historyFile;
    private $historySize;
    private $eraseDuplicates;
    private $manualDbFile;
    private $hasReadline;
    private $useReadline;
    private $useBracketedPaste;
    private $hasPcntl;
    private $usePcntl;
    private $newCommands       = [];
    private $requireSemicolons 