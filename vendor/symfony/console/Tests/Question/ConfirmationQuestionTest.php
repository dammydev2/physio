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

use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;

/**
 * CSS selector token stream.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class TokenStream
{
    /**
     * @var Token[]
     */
    private $tokens = [];

    /**
     * @var Token[]
     */
    private $used = [];

    /**
     * @var int
     */
    private $cursor = 0;

    /**
     * @var Token|null
     */
    private $peeked;

    /**
     * @var bool
     */
    private $peeking = false;

    /**
     * Pushes a token.
     *
     * @return $this
     */
    public function push(Token $token)
    {
        $this->tokens[] = $token;

        return $this;
    }

    /**
     * Freezes stream.
     *
     * @return $this
     */
    public function freeze()
    {
        return $this;
    }

    /**
     * Returns next token.
     *
     * @return Token
     *
     * @throws InternalErrorException If there is no more token
     */
    public function getNext()
    {
        if ($this->peeking) {
            $this->peeking = false;
            $this->used[] = $this->peeked;

            return $this->peeked;
        }

        if (!isset($this->tokens[$this->cursor])) {
            throw new InternalErrorException('Unexpected token stream end.');
        }

        return $this->t