<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Test\Sudo;

use PhpParser\NodeTraverser;
use Psy\Sudo\SudoVisitor;
use Psy\Test\ParserTestCase;

class SudoVisitorTest extends ParserTestCase
{
    public function setUp()
    {
        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor(new SudoVisitor());
    }

    /**
     * @dataProvider propertyFetches
     */
    public function testPropertyFetch($from, $to)
    {
        $this->assertProcessesAs($from, $to);
    }

    public function propertyFetches()
    {
        return [
            ['$a->b', "\Psy\Sudo::fetchProperty(\$a, 'b');"],
            ['$a->$b', '\Psy\Sudo::fetchProperty($a, $b);'],
            ["\$a->{'b'}", "\Psy\Sudo::fetchProperty(\$a, 'b');"],
        ];
    }

    /**
     * @dataProvider propertyAssigns
     */
    public function testPropertyAssign($from, $to)
    {
        $this->assertProcessesAs($from, $to);
    }

    public function propertyAssigns()
    {
        return [
            ['$a->b = $c', "\Psy\Sudo::assignProperty(\$a, 'b', \$c);"],
            ['$a->$b = $c', '\Psy\Sudo::assignProperty($a, $b, $c);'],
            ["\$a->{'b'} = \$c", "\Psy\Sudo::assignProperty(\$a, 'b', \$c);"],
        ];
    }

    /**
     * @dataProvider methodCalls
     */
    public function testMethodCall($from, $to)
    {
        $this->assertProcessesAs($from, $to);
    }

    public function methodCalls()
    {
        return [
            ['$a->b()', "\Psy\Sudo::callMethod(\$a, 'b');"],
            ['$a->$b()', '\Psy\Sudo::callMethod($a, $b);'],
            ["\$a->b(\$c, 'd')", "\Psy\Sudo::callMethod(\$a, 'b', \$c, 'd');"],
            ["\$a->\$b(\