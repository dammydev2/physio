<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\CssSelector\XPath;

use Symfony\Component\CssSelector\Exception\ExpressionErrorException;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Symfony\Component\CssSelector\Node\NodeInterface;
use Symfony\Component\CssSelector\Node\SelectorNode;
use Symfony\Component\CssSelector\Parser\Parser;
use Symfony\Component\CssSelector\Parser\ParserInterface;

/**
 * XPath expression translator interface.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class Translator implements TranslatorInterface
{
    private $mainParser;

    /**
     * @var ParserInterface[]
     */
    private $shortcutParsers = [];

    /**
     * @var Extension\ExtensionInterface[]
     */
    private $extensions = [];

    private $nodeTranslators = [];
    private $combinationTranslators = [];
    private $functionTranslators = [];
    private $pseudoClassTranslators = [];
    private $attributeMatchingTranslators = [];

    public function __construct(ParserInterface $parser = null)
    {
        $this->mainParser = $parser ?: new Parser();

        $this
            ->registerExtension(new Extension\NodeExtension())
            ->registerExtension(new Extension\CombinationExtension())
            ->registerExtension(new Extension\FunctionExtension())
            ->registerExtension(new Extension\PseudoClassExtension())
            ->registerExtension(new Extension\AttributeMatchingExtension())
        ;
    }

    public static function getXpathLiteral(string $element): string
    {
        if (false === strpos($element, "'")) {
            return "'".$element."'";
        }

        if (false === strpos($element, '"')) {
            return '"'.$element.'"';
        }

        $string = $element;
        $parts = [];
        while (true) {
            if (false !== $pos = strpos($string, "'")) {
                $parts[] = sprintf("'%s'", substr($string, 0, $pos));
                $parts[] = "\"'\"";
                $string = substr($string, $pos + 1);
            } else {
                $parts[]