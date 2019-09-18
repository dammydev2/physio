<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\VarDumper\Caster;

use Symfony\Component\VarDumper\Cloner\Stub;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 * @author Jan Schädlich <jan.schaedlich@sensiolabs.de>
 */
class IntlCaster
{
    public static function castMessageFormatter(\MessageFormatter $c, array $a, Stub $stub, $isNested)
    {
        $a += [
            Caster::PREFIX_VIRTUAL.'locale' => $c->getLocale(),
            Caster::PREFIX_VIRTUAL.'pattern' => $c->getPattern(),
        ];

        return self::castError($c, $a);
    }

    public static function castNumberFormatter(\NumberFormatter $c, array $a, Stub $stub, $isNested, $filter = 0)
    {
        $a += [
            Caster::PREFIX_VIRTUAL.'locale' => $c->getLocale(),
            Caster::PREFIX_VIRTUAL.'pattern' => $c->getPattern(),
        ];

        if ($filter & Caster::EXCLUDE_VERBOSE) {
            $stub->cut += 3;

            return self::castError($c, $a);
        }

        $a += [
            Caster::PREFIX_VIRTUAL.'attributes' => new EnumStub(
                [
                    'PARSE_INT_ONLY' => $c->getAttribute(\NumberFormatter::PARSE_INT_ONLY),
                    'GROUPING_USED' => $c->getAttribute(\NumberFormatter::GROUPING_USED),
                    'DECIMAL_ALWAYS_SHOWN' => $c->getAttribute(\NumberFormatter::DECIMAL_ALWAYS_SHOWN),
                    'MAX_INTEGER_DIGITS' => $c->getAttribute(\NumberFormatter::MAX_INTEGER_DIGITS),
                    'MIN_INTEGER_DIGITS' => $c->getAttribute(\NumberFormatter::MIN_INTEGER_DIGITS),
                    'INTEGER_DIGITS' => $c->getAttribute(\NumberFormatter::INTEGER_DIGITS),
                    'MAX_FRACTION_DIGITS' => $c->getAttribute(\NumberFormatter::MAX_FRACTION_DIGITS),
         