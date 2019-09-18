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

use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified as FullyQualifiedName;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Throw_;
use PhpParser\PrettyPrinter\Standard as Printer;
use Psy\Context;
use Psy\ContextAware;
use Psy\Input\CodeArgument;
use Psy\ParserFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Throw an exception or error out of the Psy Shell.
 */
class ThrowUpCommand extends Command implements ContextAware
{
    const THROW_CLASS = 'Psy\Exception\ThrowUpException';

    private $parser;
    private $printer;

    /**
     * Context instance (for ContextAware interface).
     *
     * @var Context
     */
    protected $context;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        $parserFactory = new ParserFactory();

        $this->parser  = $parserFactory->createParser();
        $this->printer = new Printer();

        parent::__construct($name);
    }

    /**
     * ContextAware interface.
     *
     * @param Context $context
     */
    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('throw-up')
            ->setDefinition([
                new CodeArgument('exception', CodeArgument::OPTIONAL, 'Exception or Error to throw.'),
            ])
            ->setDescription('Throw an exception or error out of the Psy Shell.')
            ->setHelp(
                <<<'HELP'
Throws an exception or error out of the current the Psy Shell instance.

By default it throws the most recent exception.

e.g.
<return>>>> throw-up</return>
<return>>>> throw-up $e</return>
<return>>>> throw-up new Exception('WHEEEEEE!')</retu