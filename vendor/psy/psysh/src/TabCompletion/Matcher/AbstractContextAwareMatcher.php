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

use Psy\CodeCleaner\ListPass;

class ListPassTest extends CodeCleanerTestCase
{
    public function setUp()
    {
        $this->setPass(new ListPass());
    }

    /**
     * @dataProvider invalidStatements
     * @expectedException \Psy\Exception\ParseErrorException
     */
    public function testProcessInvalidStatement($code, $expectedMessage)
    {
        if (\method_exists($this, 'setExpectedException')) {
            $this->setExpectedException('Psy\Exception\ParseErrorException', $expectedMessage);
        } else {
            $this->expectExceptionMessage($expectedMessage);
        }

        $stmts = $this->parse($code);
        $this->traverser->traverse($stmts);
    }

    public function invalidStatements()
    {
        // Not typo.  It is ambiguous whether "Syntax" or "syntax".
        $errorShortListAssign = "yntax error, unexpected '='";
        $errorEmptyList = 'Cannot use empty list';
        $errorAssocListAssign = 'Syntax error, unexpected T_CONSTANT_ENCAPSED_STRING, expecting \',\' or \')\'';
        $errorNonVariableAssign = 'Assignmen