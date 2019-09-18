<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\VarDumper\Cloner;

use Symfony\Component\VarDumper\Caster\Caster;
use Symfony\Component\VarDumper\Exception\ThrowingCasterException;

/**
 * AbstractCloner implements a generic caster mechanism for objects and resources.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
abstract class AbstractCloner implements ClonerInterface
{
    public static $defaultCasters = [
        '__PHP_Incomplete_Class' => ['Symfony\Component\VarDumper\Caster\Caster', 'castPhpIncompleteClass'],

        'Symfony\Component\VarDumper\Caster\CutStub' => ['Symfony\Component\VarDumper\Caster\StubCaster', 'castStub'],
        'Symfony\Component\VarDumper\Caster\CutArrayStub' => ['Symfony\Component\VarDumper\Caster\StubCaster', 'castCutArray'],
        'Symfony\Component\VarDumper\Caster\ConstStub' => ['Symfony\Component\VarDumper\Caster\StubCaster', 'castStub'],
        'Symfony\Component\VarDumper\Caster\EnumStub' => ['Symfony\Component\VarDumper\Caster\StubCaster', 'castEnum'],

        'Closure' => ['Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castClosure'],
        'Generator' => ['Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castGenerator'],
        'ReflectionType' => ['Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castType'],
        'ReflectionGenerator' => ['Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castReflectionGenerator'],
        'ReflectionClass' => ['Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castClass'],
        'ReflectionFunctionAbstract' => ['Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castFunctionAbstract'],
        'ReflectionMethod' => ['Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castMethod'],
        'ReflectionParameter' => ['Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castParameter'],
        'ReflectionProperty' => ['Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castProperty'],
        'ReflectionExtension' => ['Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castExtension'],
        'ReflectionZendExtension' => ['Symfony\Component\VarDumper\Caster\ReflectionCaster', 'castZendExtension'],

        'Doctrine\Common\Persistence\ObjectManager' => ['Symfony\Component\VarDumper\Caster\StubC