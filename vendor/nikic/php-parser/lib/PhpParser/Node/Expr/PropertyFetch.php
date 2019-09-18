<?php

namespace PhpParser\Parser;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt;

/* This is an automatically GENERATED file, which should not be manually edited.
 * Instead edit one of the following:
 *  * the grammar files grammar/php5.y or grammar/php7.y
 *  * the skeleton file grammar/parser.template
 *  * the preprocessing script grammar/rebuildParsers.php
 */
class Php5 extends \PhpParser\ParserAbstract
{
    protected $tokenToSymbolMapSize = 393;
    protected $actionTableSize = 1111;
    protected $gotoTableSize = 647;

    protected $invalidSymbol = 158;
    protected $errorSymbol = 1;
    protected $defaultAction = -32766;
    protected $unexpectedTokenRule = 32767;

    protected $YY2TBLSTATE = 405;
    protected $numNonLeafStates = 674;

    protected $symbolToName = array(
        "EOF",
        "error",
        "T_INCLUDE",
       