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

use Symfony\Component\CssSelector\Node;
use Symfony\Component\CssSelector\XPath\Translator;
use Symfony\Component\CssSelector\XPath\XPathExpr;

/**
 * XPath expression translator node extension.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class NodeExtension extends AbstractExtension
{
    const ELEMENT_NAME_IN_LOWER_CASE = 1;
    const ATTRIBUTE_NAME_IN_LOWER_CASE = 2;
    const ATTRIBUTE_VALUE_IN_LOWER_CASE = 4;

    private $flags;

    public function __construct(int $flags = 0)
    {
        $this->flags = $flags;
    }

    /**
     * @return $this
     */
    public function setFlag(int $flag, bool $on)
    {
        if ($on && !$this->hasFlag($flag)) {
            $this->flags += $flag;
        }

        if (!$on && $this->