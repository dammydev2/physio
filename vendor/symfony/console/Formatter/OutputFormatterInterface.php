<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Input;

use Symfony\Component\Console\Exception\InvalidArgumentException;

/**
 * StringInput represents an input provided as a string.
 *
 * Usage:
 *
 *     $input = new StringInput('foo --bar="foobar"');
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class StringInput extends ArgvInput
{
    const REGEX_STRING = '([^\s]+?)(?:\s|(?<!\\\\)"|(?<!\\\\)\'|$)';
    const REGEX_QUOTED_STRING = '(?:"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"|\'([^\'\\\\]*(?:\\\\.[^\'\\\\]*)*)\')';

    /**
     * @param string $input A string representing the parameters from the CLI
     */
    public function __construct(string $input)
    {
        parent::__construct([]);

        $this->setTokens($this->tokenize($input));
    }

    /**
     * Tokenizes a string.
     *
     * @param string $input The input to tokenize
     *
     * @return array An array of tokens
     *
     * @throws InvalidArgumentException When unable to parse input (should never happen)
     */
    private function tokenize($input)
    {
        $tokens = [];
        $length = \strlen($input);
        $cursor = 0;
        while ($cursor < $length) {
            if (preg_match('/\s+/A', $input, $match, null, $cursor)) {
            } elseif (preg_match('/([^="\'\s]+?)(=?)('.self::REGEX_QUOTED_STRING.'+)/A', $input, $match, null, $cursor)) {
                $tokens[] = $match[1].$match[2].stripcslashes(str_replace(['"\'', '\'"', '\'\'', '""'], '', substr