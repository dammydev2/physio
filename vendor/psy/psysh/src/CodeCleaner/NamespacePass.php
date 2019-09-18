<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Command\ListCommand;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Property Enumerator class.
 */
class PropertyEnumerator extends Enumerator
{
    /**
     * {@inheritdoc}
     */
    protected function listItems(InputInterface $input, \Reflector $reflector = null, $target = null)
    {
        // only list properties when a Reflector is present.

        if ($reflector === null) {
            return;
        }

        // We can only list properties on actual class (or object) reflectors.
        if (!$reflector instanceof \ReflectionClass) {
            return;
        }

        // only list properties if we are specifically asked
        if (!$input->getOption('properties')) {
            return;
        }

        $showAll    = $input->getOption('all');
        $noInherit  = $input->getOption('no-inherit');
        $properties = $this->prepareProperties($this->getProperties($showAll, $reflector, $noInherit), $target);

        if (empty($properties)) {
            return;
        }

        $ret = [];
        $ret[$this->getKindLabel($reflector)] = $properties;

        return $ret;
    }

    /**
     * Get defined properties for the given class or object Reflector.
     *
     * @param bool       $showAll   Include private and protected properties
     * @param \Reflector $reflector
     * @param bool       $noInherit Exclude inherited properties
     *
     * @return array
     */
    protected function getProperties($showAll, \Reflector $reflector, $noInherit = false)
    {
        $className = $reflector->getName();

        $properties = [];
        foreach ($reflector->getProperties() as $property) {
            if ($noInherit && $property->getDeclaringClass()->getName() !== $className) {
                continue;
            }

            if ($showAll || $property->isPublic()) {
                $properties[$property->getName()] = $property;
            }
        }

        \ksort($properties, SORT_NATURAL | SORT_FLAG_CASE);

        return $properties;
    }

    /**
     * Prepare formatted property array.
     *
     * @param array $properties
     *
     * @return array
     */
    protected function preparePropertie