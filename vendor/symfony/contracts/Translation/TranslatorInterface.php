<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\CssSelector\Tests\Parser;

use PHPUnit\Framework\TestCase;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Symfony\Component\CssSelector\Node\SelectorNode;
use Symfony\Component\CssSelector\Parser\Parser;
use Symfony\Component\CssSelector\Parser\Token;

class ParserTest extends TestCase
{
    /** @dataProvider getParserTestData */
    public function testParser($source, $representation)
    {
        $parser = new Parser();

        $this->assertEquals($representation, array_map(function (SelectorNode $node) {
            return (string) $node->getTree();
        }, $parser->parse($source)));
    }

    /** @dataProvider getParserExceptionTestData */
    public function testParserException($source, $message)
    {
        $parser = new Parser();

        try {
            $parser->parse($source);
            $this->fail('Parser should throw a SyntaxErrorException.');
        } catch (SyntaxErrorException $e) {
            $this->assertEquals($message, $e->getMessage());
        }
    }

    /** @dataProvider getPseudoElementsTestData */
    public function testPseudoElements($source, $element, $pseudo)
    {
        $parser = new Parser();
        $selectors = $parser->parse($source);
        $this->assertCount(1, $selectors);

        /** @var SelectorNode $selector */
        $selector = $selectors[0];
        $this->assertEquals($element, (string) $selector->getTree());
        $this->assertEquals($pseudo, (string) $selector->getPseudoElement());
    }

    /** @dataProvider getSpecificityTestData */
    public function testSpecificity($source, $value)
    {
        $parser = new Parser();
        $selectors = $parser->parse($source);
        $this->assertCount(1, $selectors);

        /** @var SelectorNode $selector */
        $selector = $selectors[0];
        $this->assertEquals($value, $selector->getSpecificity()->getValue());
    }

    /** @dataProvider getParseSeriesTestData */
    public function testParseSeries($series, $a, $b)
    {
        $parser = new Parser();
        $selectors = $parser->parse(sprintf(':nth-child(%s)', $series));
        $this->assertCount(1, $selectors);

        /** @var Functio