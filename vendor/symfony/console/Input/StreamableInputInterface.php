<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Command\ListCommand;
use Symfony\Component\Console\Tester\CommandTester;

class HelpCommandTest extends TestCase
{
    public function testExecuteForCommandAlias()
    {
        $command = new HelpCommand();
        $command->setApplication(new Application());
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command_name' => 'li'], ['decorated' => false]);
        $this->assertContains