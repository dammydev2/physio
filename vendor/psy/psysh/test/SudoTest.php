<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Test\Input;

use Psy\Input\CodeArgument;
use Psy\Input\ShellInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

class ShellInputTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unexpected CodeArgument before the final position: a
     */
    public function testThrowsWhenCodeArgumentNotInFinalPosition()
    {
        $definition = new InputDefinition([
            new CodeArgument('a', null, CodeArgument::REQUIRED),
            new InputArgument('b', null, InputArgument::REQUIRED),
        ]);

        $input = new ShellInput('foo bar');
        $input->bind($definition);
    }

    public function testInputOptionWithGivenString()
    {
        $definition = new InputDefinition([
            new InputOption('foo', null, InputOption::VALUE_REQUIRED),
            new CodeArgument('code', null, CodeArgument::REQUIRED),
        ]);

        $input = new ShellInput('--foo=bar echo "baz\\\\n";');
        $input->bind($definition);
        $this->assertSame('bar', $input->getOption('foo'));
        $this->assertSame('echo "baz\n";', $input->getArgument('code'));
    }

    public function testInputOptionWithoutCodeArguments()
    {
        $definition = new InputDefinition([
            new InputOption('foo', null, InputOption::VALUE_REQUIRED),
            new InputOption('qux', 'q', InputOption::VALUE_REQUIRED),
            new InputArgument('bar', null, InputArgument::REQUIRED),
            new InputArgument('baz', null, InputArgument::REQUIRED),
        ]);

        $input = new ShellInput('--foo=foo -q qux bar "baz\\\\n"');
        $input->bind($definition);
        $this->assertSame('foo', $input->getOption('foo'));
        $this->assertSame('qux', $input->getOption('qux'));
        $this->assertSame('bar', $input->getArgument('bar'));
        $this->assertSame('baz\\n', $input->getArgument('baz'));
    }

    public function testInputWithDashDash()
    {
        $definition = new InputDefinition([
            new InputOption('foo', null, InputOption::VALUE_REQUIRED),
            new CodeArgument('code', null, CodeArgument::REQUIRED),
        ]);

        $input = new ShellInput('-- echo --foo::$bar');
        $input->bind($definition);
        $this->assertNull($input->getOption('foo'));
        $this->assertSame('echo --foo::$bar', $input->getArgument('code'));
    }

    public function testInputWithEmptyStri