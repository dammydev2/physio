<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation;

/**
 * HTTP header utility functions.
 *
 * @author Christian Schmidt <github@chsc.dk>
 */
class HeaderUtils
{
    public const DISPOSITION_ATTACHMENT = 'attachment';
    public const DISPOSITION_INLINE = 'inline';

    /**
     * This class should not be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * Splits an HTTP header by one or more separators.
     *
     * Example:
     *
     *     HeaderUtils::split("da, en-gb;q=0.8", ",;")
     *     // => ['da'], ['en-gb', 'q=0.8']]
     *
     * @param string $header     HTTP header value
     * @param string $separators List of characters to split on, ordered by
     *                           precedence, e.g. ",", ";=", or ",;="
     *
     * @return array Nested array with as many levels as there are characters in
     *               $separators
     */
    public static function split(string $header, string $separators): array
    {
        $quotedSeparators = preg_quote($separators, '/');

        preg_match_all('
            /
                (?!\s)
                    (?:
                        # quoted-string
                        "(?:[^"\\\\]|\\\\.)*(?:"|\\\\|$)
                    |
                        # token
                        [^"'.$quotedSeparators.']+
                    )+
                (?<!\s)
            |
                # separator
                \s*
                (?<separator>['.$quotedSeparators.'])
                \s*
            /x', trim($header), $matches, PREG_SET_ORDER);

        return self::groupParts($matches, $separators);
    }

    /**
     * Combines an array of arrays into one associative array.
     *
     * Each of the nested arrays should have one or two elements. The first
     * value will be used as the keys in the associative array, and the second
     * will be used as the values, or true if the nested array only contains one
     * element. Array keys are lowercased.
     *
     * Example:
     *
     *     HeaderUtils::combine([["foo", "abc"], ["bar"]])
     *     // => ["foo" => "abc", "bar" => true]
     */
    public static function combine(array $parts): array
    {
        $assoc = [];
        foreach ($parts as $part) {
            $name = strtolower($part[0]);
            $value = $part[1] ?? true;
            $assoc[$name] = $value;
        }

        return $assoc;
    }

    /**
     * Joins an associative array into a string for use in an HTTP header.
     *
     * The key and value of each entry are joined with "=", and all entries
     * are joined with the specified separator and an additional space (for
     * readability). Values are quoted if necessary.
     *
     * Example:
     *
     *     HeaderUtils::toString(["foo" => "abc", "bar" => true, "baz" => "a b c"], ",")
     *     // => 'foo=abc, bar, baz="a b c"'
     */
    public static function toString(array $assoc, string $separator): string
