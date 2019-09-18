<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\CssSelector\Parser;

use Symfony\Component\CssSelector\Exception\SyntaxErrorException;
use Symfony\Component\CssSelector\Node;
use Symfony\Component\CssSelector\Parser\Tokenizer\Tokenizer;

/**
 * CSS selector parser.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class Parser implements ParserInterface
{
    private $tokenizer;

    public function __construct(Tokenizer $tokenizer = null)
    {
        $this->tokenizer = $tokenizer ?: new Tokenizer();
    }

    /**
     * {@inheritdoc}
     */
    public function parse(string $source): array
    {
        $reader = new Reader($source);
        $stream = $this->tokenizer->tokenize($reader);

        return $this->parseSelectorList($stream);
    }

    /**
     * Parses the arguments for ":nth-child()" and friends.
     *
     * @param Token[] $tokens
     *
     * @throws SyntaxErrorException
     */
    public static function parseSeries(array $tokens): array
    {
        foreach ($tokens as $token) {
            if ($token->isString()) {
                throw SyntaxErrorException::stringAsFunctionArgument();
            }
        }

        $joined = trim(implode('', array_map(function (Token $token) {
            return $token->getValue();
        }, $tokens)));

        $int = function ($string) {
            if (!is_numeric($string)) {
                throw SyntaxErrorException::stringAsFunctionArgument();
            }

            return (int) $string;
        };

        switch (true) {
            case 'odd' === $joined:
                return [2, 1];
            case 'even' === $joined:
                return [2, 0];
            case 'n' === $joined:
                return [1, 0];
            case false === strpos($joined, 'n'):
                return [0, $int($joined)];
        }

        $split = explode('n', $joined);
        $first = isset($split[0]) ? $split[0] : null;

        return [
            $first ? ('-' === $first || '+' === $first ? $int($first.'1') : $int($first)) : 1,
            isset($split[1]) && $split[1] ? $int($split[1]) : 0,
        ];
    }

    private function parseSelectorList(TokenStream $stream): array
    {
        $stream->skipWhitespace();
        $selectors = [];

        while (true) {
            $selectors[] = $this->parserSelectorNode($stream);

            if ($stream->getPeek()->isDelimiter([','])) {
                $stream->getNext();
                $stream->skipWhitespace();
            } else {
                break;
            }
        }

        return $selectors;
    }

    private function parserSelectorNode(TokenStream $stream): Node\SelectorNode
    {
        list($result, $pseudoElement) = $this->parseSimpleSelector($stream);

        while (true) {
            $stream->skipWhitespace();
            $peek = $stream->getPeek();

            if ($peek->isFileEnd() || $peek->isDelimiter([','])) {
                break;
            }

            if (null !== $pseudoElement) {
                throw SyntaxErrorException::pseudoElementFound($pseudoElement, 'not at the end of a selector');
            }

            if ($peek->isDelimiter(['+', '>', '~'])) {
                $combinator = $stream->getNext()->getValue();
                $stream->skipWhitespace();
            } else {
                $combinator = ' ';
            }

            list($nextSelector, $pseudoElement) = $this->parseSimpleSelector($stream);
            $result = new Node\CombinedSelectorNode($result, $combinator, $nextSelector);
        }

        return new Node\SelectorNode($result, $pseudoElement);
    }

    /**
     * Parses next simple node (hash, class, pseudo, negation).
     *
     * @throws SyntaxErrorException
     */
    private function parseSimpleSelector(TokenStream $stream, bool $insideNegation = false): array
    {
        $stream->skipWhitespace();

        $selectorStart = \count($stream->getUsed());
        $result = $this->parseElementNode($stream);
        $pseudoElement = null;

        while (true) {
            $peek = $stream->getPeek();
            if ($peek->isWhitespace()
                || $peek->isFileEnd()
                || $peek->isDelimiter([',', '+', '>', '~'])
                || ($insideNegation && $peek->isDelimiter([')']))
            ) {
                break;
            }

            if (null !== $pseudoElement) {
                throw SyntaxErrorException::pseudoElementFound($pseudoElement, 'not at the end of a selector');
            }

            if ($peek->isHash()) {
                $result = new Node\HashNode($result, $stream->getNext()->getValue());
            } elseif ($peek->isDelimiter(['.'])) {
                $stream->getNext();
                $result = new Node\ClassNode($result, $stream->getNextIdentifier());
            } elseif ($peek->isDelimiter(['['])) {
                $stream->getNext();
                $result = $this->parseAttributeNode($result, $stream);
            } elseif ($peek->isDelimiter([':'])) {
                $stream->getNext();

                if ($stream->getPeek()->isDelimiter([':'])) {
                    $stream->getNext();
                    $pseudoElement = $stream->getNextIdentifier();

                    continue;
                }

                $identifier = $stream->getNextIdentifier();
                if (\in_array(strtolower($identifier), ['first-line', 'first-letter', 'before', 'after'])) {
                    // Special case: CSS 2.1 pseudo-elements can have a single ':'.
                    // Any new pseudo-element must have two.
                    $pseudoElement = $identifier;

                    continue;
                }

                if (!$stream->getPeek()->isDelimiter(['('])) {
                    $result = new Node\PseudoNode($result, $identifier);

                    continue;
                }

                $stream->getNext();
                $stream->skipWhitespace();

                if ('not' === strtolower($identifier)) {
                    if ($insideNegation) {
          