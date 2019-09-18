<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Test;

use Psy\Configuration;
use Psy\Exception\ErrorException;
use Psy\Exception\ParseErrorException;
use Psy\Shell;
use Psy\TabCompletion\Matcher\ClassMethodsMatcher;
use Symfony\Component\Console\Output\StreamOutput;

class ShellTest extends \PHPUnit\Framework\TestCase
{
    private $streams = [];

    public function tearDown()
    {
        foreach ($this->streams as $stream) {
            \fclose($stream);
        }
    }

    public function testScopeVariables()
    {
        $one       = 'banana';
        $two       = 123;
        $three     = new \StdClass();
     