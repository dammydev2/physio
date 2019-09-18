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

use Psy\CodeCleaner\ValidFunctionNamePass;

class ValidFunctionNamePassTest extends CodeCleanerTestCase
{
    public function setUp()
    {
        $this->setPass(new ValidFunctionNamePass());
    }

    /**
     * @dataProvider getInvalidFunctions
     * @expectedException \Psy\Exception\FatalErrorException
     */
    public function testProcessInvalidFunctionCallsAndDeclarations($code)
    {
        $this->parseAndTraverse($code);
    }

    public function getInvalidFunctions()
    {
        return [
            // function declarations
            ['function array_merge() {}'],
            ['function Array_Merge() {}'],
            ['
                function psy_test_codecleaner_validfunctionnamepass_alpha() {}
                function psy_test_codecleaner_validfunctionnamepass_alpha() {}
            '],
            ['
                namespace Psy\\Test\\CodeCleaner\\ValidFunctionNamePass {
                    function beta() {}
                }
                namespace Psy\\Test\\CodeCleaner\\ValidFunctionNamePass {
                    function beta() {}
                }
            '],

            // function calls
            ['psy_test_codecleaner_validfunctionnamepass_gamma()'],
            ['
                namespace Psy\\Test\\CodeCleaner\\ValidFunctionNamePass {
                    delta();
                }
            '],

            // recursion
            ['function a() { a(); } function a() {}'],
        ];
    }

    /**
     * @dataProvider getValidFunctions
     */
    public function testProcessValid