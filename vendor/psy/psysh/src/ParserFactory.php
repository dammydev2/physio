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

use Psy\Command\ListCommand\ClassConstantEnumerator;
use Psy\Command\ListCommand\ClassEnumerator;
use Psy\Command\ListCommand\ConstantEnumerator;
use Psy\Command\ListCommand\FunctionEnumerator;
use Psy\Command\ListCommand\GlobalVariableEnumerator;
use Psy\Command\ListCommand\MethodEnumerator;
use Psy\Command\ListCommand\PropertyEnumerator;
use Psy\Command\ListCommand\VariableEnumerator;
use Psy\Exception\RuntimeException;
use Psy\Input\CodeArgument;
use Psy\Input\FilterOptions;
use Psy\VarDumper\Presenter;
use Psy\VarDumper\PresenterAware;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * List available local variables, object properties, etc.
 */
class ListCommand extends ReflectingCommand implements PresenterAware
{
    protected $presenter;
    protected $enumerators;

    /**
     * PresenterAware interface.
     *
     * @param Presenter $presenter
     */
    public function setPresenter(Presenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        list($grep, $insensitive, $invert) = FilterOptions::getOptions();

        $this
            ->setName('ls')
            ->setAliases(['list', 'dir'])
            ->setDefinition([
                new CodeArgument('target', CodeArgument::OPTIONAL, 'A target class or object to list.'),

                new InputOption('vars',        '',  InputOption::VALUE_NONE,     'Display variables.'),
                new InputOption('constants',   'c', InputOption::VALUE_NONE,     'Display defined constants.'),
                new InputOption('functions',   'f', InputOption::VALUE_NONE,     'Display defined functions.'),
                new InputOption('classes',     'k', InputOption::VALUE_NONE,     'Display declared classes.'),
                new InputOption('interfaces',  'I', InputOption::VALUE_NONE,   