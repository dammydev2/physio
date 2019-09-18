<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Command;

use JakubOnderka\PhpConsoleHighlighter\Highlighter;
use Psy\Configuration;
use Psy\ConsoleColorFactory;
use Psy\Output\ShellOutput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Show the context of where you opened the debugger.
 */
class WhereamiCommand extends Command
{
    private $colorMode;
    private $backtrace;

    /**
     * @param null|string $colorMode (default: null)
     */
    public function __construct($colorMode = null)
    {
        $this->colorMode = $colorMode ?: Configuration::COLOR_MODE_AUTO;
        $this->backtrace = \debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('whereami')
            ->setDefinition([
                new InputOption('num', 'n', InputOption::VALUE_OPTIONAL, 'Number of lines before and after.', '5'),
            ])
            ->setDescription('Show where you are in the code.')
            ->setHelp(
                <<<'HELP'
Show where you are in the code.

Optionally, include how many lines before and after you want to display.

e.g.
<return>> whereami </return>
<return>> whereami -n10</return>
HELP
            );
    }

    /**
     * Obtains the correct stack frame in the full backtrace.
  