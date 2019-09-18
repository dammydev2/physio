<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\CssSelector\Tests\XPath;

use PHPUnit\Framework\TestCase;
use Symfony\Component\CssSelector\Node\ElementNode;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Symfony\Component\CssSelector\Parser\Parser;
use Symfony\Component\CssSelector\XPath\Extension\HtmlExtension;
use Symfony\Component\CssSelector\XPath\Translator;
use Symfony\Component\CssSelector\XPath\XPathExpr;

class TranslatorTest extends TestCase
{
    /** @dataProvider getXpathLiteralTestData */
    public function testXpathLiteral($value, $literal)
    {
        $this->assertEquals($literal, Translator::getXpathLiteral($value));
    }

    /** @dataProvider getCssToXPathTestData */
    public function testCssToXPath($css, $xpath)
    {
        $translator = new Translator();
        $translator->registerExtension(new HtmlExtension($translator));
        $this->assertEquals($xpath, $translator->cssToXPath($css, ''));
    }

    /**
     * @expectedException \Symfony\Component\CssSelector\Exception\ExpressionErrorException
     */
    public function testCssToXPathPseudoElement()
    {
        $translator = new Translator();
        $translator->registerExtension(new HtmlExtension($translator));
        $translator->cssToXPath('e::firs