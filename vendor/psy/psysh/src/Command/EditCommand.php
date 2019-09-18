<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Formatter;

use Psy\Reflection\ReflectionClassConstant;
use Psy\Reflection\ReflectionConstant_;
use Psy\Reflection\ReflectionLanguageConstruct;
use Psy\Util\Json;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * An abstract representation of a function, class or property signature.
 */
class SignatureFormatter implements Formatter
{
    /**
     * Format a signature for the given reflector.
     *
     * Defers to subclasses to do the actual formatting.
     *
     * @param \Reflector $reflector
     *
     * @return string Formatted signature
     */
    public static function format(\Reflector $reflector)
    {
        switch (true) {
            case $reflector instanceof \ReflectionFunction:
            case $reflector instanceof ReflectionLanguageConstruct:
                return self::formatFunction($reflector);

            // this case also covers \ReflectionObject:
            case $reflector instanceof \ReflectionClass:
                return self::formatClass($reflector);

            case $reflector instanceof ReflectionClassConstant:
            case $reflector instanceof \ReflectionClassConstant:
                return self::formatClassConstant($reflector);

            case $reflector instanceof \ReflectionMethod:
                return self::formatMethod($reflector);

            case $reflector instanceof \ReflectionProperty:
                return self::formatProperty($reflector);

            case $reflector instanceof ReflectionConstant_:
                return self::formatConstant($reflector);

            default:
                throw new \InvalidArgumentException('Unexpected Reflector class: ' . \get_class($reflector));
        }
    }

    /**
     * Print the signature name.
     *
     * @param \Reflector $reflector
     *
     * @return string Formatted name
     */
    public static function formatName(\Reflector $reflector)
    {
        return $reflector->getName();
    }

    /**
     * Print the method, property or class modifiers.
     *
     * @param \Reflector $reflector
     *
     * @return string Formatted modifiers
     */
    private static function formatModifiers(\Reflector $reflector)
    {
        if ($reflector instanceof \ReflectionClass && $reflector->isTrait()) {
            // For some reason, PHP 5.x returns `abstract public` modifiers for
            // traits. Let's just ignore that business entirely.
            if (\version_compare(PHP_VERSION, '7.0.0', '<')) {
                return [];
            }
        }

        return \implode(' ', \array_map(function ($modifier) {
            return \sprintf('<keyword>%s</keyword>', $modifier);
        }, \Reflection::getModifierNames($reflector->getModifiers())));
    }

    /**
     * Format a class signature.
     *
     * @param \ReflectionClass $reflector
     *
     * @return string Formatted signature
     */
    private static function formatClass(\ReflectionClass $reflector)
    {
        $chunks = [];

        if ($modifiers = self::formatModifiers($reflector)) {
            $chunks[] = $modifiers;
        }

        if ($reflector->isTrait()) {
            $chunks[] = 'trait';
        } else {
            $chunks[] = $reflector->isInterface() ? 'interface' : 'class';
        }

        $chunks[] = \sprintf('<class>%s</class>', self::formatName($reflector));

        if ($parent = $reflector->getParentClass()) {
            $chunks[] = 'extends';
            $chunks[] = \sprintf('<class>%s</class>', $parent->getName());
        }

        $interfaces = $reflector->getInterfaceNames();
        if (!empty($interfaces)) {
            \sort($interfaces);

            $chunks[] = 'implements';
            $chunks[] = \implode(', ', \array_map(function ($name) {
                return \sprintf('<class>%s</class>', $name);
            }, $interfaces));
        }

        return \implode(' ', $chunks);
    }

    /**
     * Format a constant signature.
     *
     * @param ReflectionClassConstant|\ReflectionClassConstant $reflector
     *
     * @return string Formatted signature
     */
    private static function formatClassConstant($reflector)
    {
        $value = $reflector->getValue();
        $style = self::getTypeStyle($value);

        return \sprintf(
            '<keyword>const</keyword> <const>%s</const> = <%s>%s</%s>',
            self::formatName($reflector),
            $style,
            OutputFormatter::escape(Json::encode($value)),
            $style
        );
    }

    /**
     * Format a constant signature.
     *
     * @param ReflectionConstant_ $reflector
     *
     * @return string Formatted signature
     */
    private static function formatConstant($reflector)
    {
        $value = $reflector->getValue();
        $style = self::getTypeStyle($value);

        return \sprintf(
            '<keyword>define</keyword>(<string>%s</string>, <%s>%s</%s>)',
            OutputFormatter::escape(Json::encode($reflector->getName())),
            $style,
            OutputFormatter::escape(Json::encode($value)),
            $style
        );
    }

    /**
     * Helper for getting output style for a given value's type.
     *
     * @param mixed $value
     *
     * @return string
     */
    private static function getTypeStyle($value)
    {
        if (\is_int($value) || \is_float($value)) {
            return 'number';
        } elseif (\is_string($value)) {
            return 'string';
        } elseif (\is_bool($value) 