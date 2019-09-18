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

use Symfony\Component\CssSelector\XPath\Translator;
use Symfony\Component\CssSelector\XPath\XPathExpr;

/**
 * XPath expression translator attribute extension.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class AttributeMatchingExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getAttributeMatchingTranslators()
    {
        return [
            'exists' => [$this, 'translateExists'],
            '=' => [$this, 'translateEquals'],
            '~=' => [$this, 'translateIncludes'],
            '|=' => [$this, 'translateDashMatch'],
            '^=' => [$this, 'translatePrefixMatch'],
            '$=' => [$this, 'translateSuffixMatch'],
            '*=' => [$this, 'translateSubstringMatch'],
            '!=' => [$this, 'translateDifferent'],
        ];
    }

    public function translateExists(XPathExpr $xpath, string $attribute, ?string $value): XPathExpr
    {
        return $xpath->addCondition($attribute);
    }

    public function translateEquals(XPathExpr $xpath, string $attribute, ?string $value): XPathExpr
    {
        return $xpath->addCondition(sprintf('%s = %s', $