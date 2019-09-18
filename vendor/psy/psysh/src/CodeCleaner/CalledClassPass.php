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

use PhpParser\NodeTraverser;
use PhpParser\PrettyPrinter\Standard as Printer;
use Psy\Command\TimeitCommand\TimeitVisitor;
use Psy\Input\CodeArgument;
use Psy\ParserFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TimeitCommand.
 */
class TimeitCommand extends Command
{
    const RESULT_MSG     = '<info>Command took %.6f seconds to complete.</info>';
    const AVG_RESULT_MSG = '<info>Command took %.6f seconds on average (%.6f median; %.6f total) to complete.</info>';

    private static $start = null;
    private static $times = [];

    private $parser;
    private $traverser;
    private $printer;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        $parserFactory = new ParserFactory();
        $this->parser = $parserFactory->createParser();

        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor(new TimeitVisitor());

        $this->printer = new Printer();

        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('timeit')
            ->setDefinition([
                new InputOption('num', 'n', InputOption::VALUE_REQUIRED, 'Number of iterations.'),
                new CodeArgument('code', CodeArgument::REQUIRED, 'Code to execute.'),
            ])
            ->setDescription('Profiles with a timer.')
            ->setHelp(
                <<<'HELP'
Time profiling for functions and commands.

e.g.
<return>>>> timeit sleep(1)</return>
<return>>>> timeit -n1000 $closure()</return>
HELP
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $code = $input->getArgument('code');
        $num = $input->getOption('num') ?: 1;
        $shell = $this->getApplication();

        $instrumentedCode = $this->instrumentCode($code);

        self::$times = [];

        for ($i = 0; $i < $num; $i++) {
            $_ = $shell->e