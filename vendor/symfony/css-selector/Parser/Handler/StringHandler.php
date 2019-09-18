<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\CssSelector\XPath\Extension;

use Symfony\Component\CssSelector\Exception\ExpressionErrorException;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Symfony\Component\CssSelector\Parser\Parser;
use Symfony\Component\CssSelector\XPath\Translator;
use Symfony\Component\CssSelector\XPath\XPathExpr;

/**
 * XPath expression translator function extension.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class FunctionExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctionTranslators()
    {
        return [
            'nth-child' => [$this, 'translateNthChild'],
            'nth-last-child' => [$this, 'translateNthLastChild'],
            'nth-of-type' => [$this, 'translateNthOfType'],
            'nth-last-of-type' => [$this, 'translateNthLastOfType'],
            'contains' => [$this, 'translateContains'],
            'lang' => [$this, 'translateLang'],
        ];
    }

    /**
     * @throws ExpressionErrorException
     */
    public function translateNthChild(XPathExpr $xpath, FunctionNode $function, bool $last = false, bool $addNameTest = true): XPathExpr
    {
        try {
            list($a, $b) = Parser::parseSeries($function->getArguments());
        } catch (SyntaxErrorException $e) {
            throw new ExpressionErrorException(sprintf('Invalid series: %s', implode(', ', $function->getArguments())), 0, $e);
        }

        $xpath->addStarPrefix();
        if ($addNameTest) {
            $xpath->addNameTest();
        }

        if (0 === $a) {
            return $xpath->addCondition('position() = '.($last ? 'last() - '.($b - 1) : $b));
        }

        if ($a < 0) {
            if ($b < 1) {
                return $xpath->addCondition('false()');
            }

            $sign = '<=';
        } else {
            $sign = '>=';
        }

        $expr = 'position()';

        if ($last) {
            $expr = '