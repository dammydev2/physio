<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Test\Formatter;

use Psy\Formatter\CodeFormatter;
use Psy\Test\Formatter\Fixtures\SomeClass;

class CodeFormatterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider reflectors
     */
    public function testFormat($reflector, $expected)
    {
        $formatted = CodeFormatter::format($reflector);
        $formattedWithoutColors = \preg_replace('#' . \chr(27) . '\[\d\d?m#', '', $formatted);

        $this->assertEquals($expected, self::trimLines($formattedWithoutColors));
        $this->assertNotEquals($expected, self::trimLines($formatted));
    }

    public function reflectors()
    {
        $expectClass = <<<'EOS'
  > 14| class SomeClass
    15| {
    16|     const SOME_CONST = 'some const';
    17|     private $someProp = 'some prop';
    18|
    19|     public function someMethod($someParam)
    20|     {
    21|         return 'some method';
    22|     }
    23|
    24|     public static function someClosure()
    25|     {
    26|         return function () {
    27|             return 'some closure';
    28|         };
    29|     }
    30| }
EOS;

        $expectMethod = <<<'EOS'
  > 19|     public function someMethod($someParam)
    20|     {
    21|         return 'some method';
    22|     }
EOS;

        $expectClosure = <<<'EOS'
  > 26|         return function () {
    27|             return 'some closure';
    28|         };
EOS;

        return [
            [new \ReflectionClass('Psy\Test\Formatter\Fixtures\SomeClass'), $expectClass],
            [new \ReflectionObject(new SomeClass()), $expectClass],
            [new \ReflectionMethod('Psy\Test\Formatter\Fixtures\SomeClass', 'someMethod'), $expectMethod],
            [new \ReflectionFunction(SomeClass::someClosure()), $expectClosure],
        ];
    }

    /**
     * @dataProvider invalidReflectors
     * @expectedException \Psy\Exception\RuntimeException
     */
    public function testCodeFormatterThrowsExceptionForReflectorsItDoesntUnderstand($reflector)
    {
        CodeFormatter::format($reflector);
    }

    public function invalidReflectors()
    {
        $reflectors = [
            [new \ReflectionExtension('json')],
            [new \ReflectionParameter(['Psy\Test\Formatter\Fixtures\S