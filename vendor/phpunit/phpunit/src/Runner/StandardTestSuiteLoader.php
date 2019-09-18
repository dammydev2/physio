<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util;

use PHPUnit\Framework\Exception;

/**
 * Command-line options parsing class.
 */
final class Getopt
{
    /**
     * @throws Exception
     */
    public static function getopt(array $args, string $short_options, array $long_options = null): array
    {
        if (empty($args)) {
            return [[], []];
        }

        $opts     = [];
        $non_opts = [];

        if ($long_options) {
            \sort($long_options);
        }

        if (isset($args[0][0]) && $args[0][0] !== '-') {
            \array_shift($args);
        }

        \reset($args);

        $args = \array_map('trim', $args);

        /* @noinspection ComparisonOperandsOrderInspection */
        while (false !== $arg = \current($args)) {
            $i = \key($args);
            \next($args);

            if ($arg === '') {
                continue;
            }

            if ($arg === '--') {
                $non_opts = \array_merge($non_opts, \array_slice($args, $i + 1));

                break;
            }

            if ($arg[0] !== '-' || (\strlen($arg) > 1 && $arg[1] === '-' && !$long_options)) {
                $non_opts[] = $args[$i];

                continue;
            }

            if (\strlen($arg) > 1 && $arg[1] === '-') {
                self::parseLongOption(
                    \substr($arg, 2),
                    $long_options,
                    $opts,
                    $args
                );
            } else {
                self::parseShortOption(
                    \substr($arg, 1),
                    $short_options,
                    $opts,
                    $args
                );
            }
        }

        return [$opts, $non_opts];
    }

    /**
     * @throws Exception
     */
    private static function parseShortOption(string $arg, string $short_options, array &$opts, array &$args): void
    {
        $argLen = \strlen($arg);

        for ($i = 0; $i < $argLen; $i++) {
            $opt     = $arg[$i];
            $opt_arg = null;

            if ($arg[$i] === ':' || ($spec = \strstr($short_options, $opt)) === false) {
                throw new Exception(
                    "unrecognized option -- $opt"
                );
            }

            if (\strlen($spec) > 1 && $spec[1] === ':') {
                if ($i + 1 < $argLen) {
                    $opts[] = [$opt, \substr($arg, $i + 1)];

                    break;
                }

                if (!(\strlen($spec) > 2 && $spec[2] === ':')) {
                    /* @noinspection ComparisonOperandsOrderInspection */
                    if (false === $opt_arg = \current($args)) {
                        throw new Exception(
                            "option requires an argument -- $opt"
                        );
                    }

                    \next($args);
                }
            }

            $opts[] = [$opt, $opt_arg];
        }
    }

    /**
     * @throws Exception
     */
    private static function parseLongOption(string $arg, array $long_options, array &$opts, array &$args): void
    {
        $count   = \count(