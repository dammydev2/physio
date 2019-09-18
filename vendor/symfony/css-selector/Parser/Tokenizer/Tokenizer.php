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
use Symfony\Component\CssSelector\XPath\XPathExpr;

/**
 * XPath expression translator pseudo-class extension.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class PseudoClassExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getPseudoClassTranslators()
    {
        return [
            'root' => [$this, 'translateRoot'],
            'first-child' => [$this, 'translateFirstChild'],
            'last-child' => [$this, 'translateLastChild'],
            'first-of-type' => [$this, 'translateFirstOfType'],
            'last-of-type' => [$this, 'translateLastOfType'],
            'only-child' => [$this, 'translateOnlyChild'],
            'only-of-type' => [$this, 'translateOnlyOfType'],
            'empty' => [$this, 'translateEmpty'],
        ];
    }

    /**
     * @return XPathExpr
     */
    public function translateRoot(XPathExpr $xpath)
    {
        return $xpath->addCondition('not(parent::*)');
    }

    /**
     * @return XPathExpr
     */
    public function translateFirstChild(XPathExpr $xpath)
    {
        return $xpath
            ->addStarPrefix()
            ->addNameTest()
            ->addCondition('position() = 1');
    }

    /**
     * @return XPathExpr
     */
    public function translateLastChild(XPathExpr $xpath)
    {
        return $xpath
            ->addStarPrefix()
            ->addNameTest()
            ->addCondition('position() = last()');
    }

    /**
     * @return XPathExpr
     *
     * @throws 