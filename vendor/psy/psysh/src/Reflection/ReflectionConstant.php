<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Test\CodeCleaner;

use Psy\CodeCleaner\ExitPass;

class ExitPassTest extends CodeCleanerTestCase
{
    /**
     * @var string
     */
    private $expectedExceptionString = '\\Psy\\Exception\\BreakException::exitShell()';

    public function setUp()
    {
        $this->setPass(new ExitPass());
    }

    /**
     * @dataProvider dataProviderExitStatement
     */
    public function testExitStatement($from, $to)
    {
        $this->assertProcessesAs($from, $to);
    }

    /**
     * Data provider for testExi