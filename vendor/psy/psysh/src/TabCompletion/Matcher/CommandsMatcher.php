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

use Psy\CodeCleaner\ValidClassNamePass;

class ValidClassNamePassTest extends CodeCleanerTestCase
{
    public function setUp()
    {
        $this->setPass(new ValidClassNamePass());
    }

    /**
     * @dataProvider getInvalid
     * @expectedException \Psy\Exception\FatalErrorException
     */
    public function testProcessInvalid($code)
    {
        $this->parseAndTraverse($code);
    }

    public function getInvalid()
    {
        // class declarations
        return [
            // core class
            ['class stdClass {}'],
            // capitalization
            ['class stdClass {}'],

            // collisions with interfaces and traits
            ['interface stdClass {}'],
            ['trait stdClass {}'],

            // collisions inside the same code snippet
            ['
                class Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
                class Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
            '],
            ['
                class Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
                trait Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
            '],
            ['
                trait Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
                class Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
            '],
            ['
                trait Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
                interface Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
            '],
            ['
                interface Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
                trait Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
            '],
            ['
                interface Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
                class Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
            '],
            ['
                class Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
                interface Psy_Test_CodeCleaner_ValidClassNamePass_Alpha {}
            '],

            // namespaced collisions
            ['
                namespace Psy\\Test\\CodeCleaner {
                    class ValidClassNamePassTest {}
                }
            '],
            ['
                namespace Psy\\Test\\CodeCleaner\\Vali