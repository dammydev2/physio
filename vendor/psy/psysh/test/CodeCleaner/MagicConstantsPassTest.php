<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Test\Util;

use Psy\Util\Docblock;

class DocblockTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider comments
     */
    public function testDocblockParsing($comment, $body, $tags)
    {
        $reflector = $this
            ->getMockBuilder('ReflectionClass')
            ->disableOriginalConstructor()
            ->getMock();

        $reflector->expects($this->once())
            ->method('getDocComment')
            ->will($this->returnValue($comment));

        $docblock = new Docblock($reflector);

        $this->assertSame($bo