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
 * Represents a list of function arguments.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class ArgsStub extends EnumStub
{
    private static $parameters = [];

    public function __construct(array $args, string $function, ?string $class)
    {
        list($variadic, $params) = self::getParameters($function, $class);

        $values = [];
        foreach ($args as $k => $v) {
            $values[$k] = !is_scalar($v) && !$v instanceof Stub ? ne