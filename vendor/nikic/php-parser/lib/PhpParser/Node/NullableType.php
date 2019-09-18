<?php declare(strict_types=1);

namespace PhpParser\Node\Stmt;

use PhpParser\Node;

class If_ extends Node\Stmt
{
    /** @var Node\Expr Condition expression */
    public $cond;
    /** @var Node\Stmt[] Statements */
    public $stmts;
    /** @var ElseIf_[] Elseif clauses */
    public $elseifs;
    /** @var null|Else_ Else clause */
    public $else;

    /**
     * Constructs an if node.
     *
     * @param Node\Expr $cond       Condition
     * @param array     $subNodes   Array of the following optional subnodes:
     *                              'stmts'   => array(): Statements
     *                              'elseifs' => array(): Elseif clauses
     *                              'else'    => null   : Else clause
     