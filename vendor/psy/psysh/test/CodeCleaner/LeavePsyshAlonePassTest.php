<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Test\TabCompletion;

use Psy\Command\ListCommand;
use Psy\Command\ShowCommand;
use Psy\Configuration;
use Psy\Context;
use Psy\ContextAware;
use Psy\TabCompletion\Matcher;

class AutoCompleterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param string $line
     * @param array  $mustContain
     * @param array  $mustNotContain
     * @dataProvider classesInput
     */
    public function testClassesCompletion($line, $mustContain, $mustNotContain)
    {
        $context = new Context();

        $commands = [
            new ShowCommand(),
            new ListCommand(),
        ];

        $matchers = [
            new Matcher\VariablesMatcher(),
            new Matcher\ClassNamesMatcher(),
            new Matcher\ConstantsMatcher(),
            new Matcher\FunctionsMatcher(),
            new Matcher\ObjectMethodsMatcher(),
            new Matcher\ObjectAttributesMatcher(),
            new Matcher\KeywordsMatcher(),
            new Matcher\ClassAttributesMatcher(),
            new Matcher\ClassMethodsMatcher(),
            new Matcher\CommandsMatcher($commands),
        ];

        $config = new Configuration();
        $tabCompletion = $config->getAutoCompleter();
        foreach ($matchers as $matcher) {
            if ($matcher instanceof ContextAware) {
   