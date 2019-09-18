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
 * Method Enumerator class.
 */
class MethodEnumerator extends Enumerator
{
    /**
     * {@inheritdoc}
     */
    protected function listItems(InputInterface $input, \Reflector $reflector = null, $target = null)
    {
        // only list methods when a Reflector is present.

        if ($reflector === null) {
            return;
        }

        // We can only list methods on actual class (or object) reflectors.
        if (!$reflector instanceof \ReflectionClass) {
            return;
        }

        // only list methods if we are specifically asked
        if (!$input->getOption('methods')) {
            return;
        }

        $showAll   = $input->getOption('all');
        $noInherit = $input->getOption('no-inherit');
        $methods   = $this->prepareMethods($this->getMethods($showAll, $reflector, $noInherit));

        if (empty($methods)) {
            return;
        }

        $ret = [];
        $ret[$this->getKindLabel($reflector)] = $methods;

        return $ret;
    }

    /**
     * Get defined methods for the given class or object Reflector.
     *
     * @param bool       $showAll   Include private and protected methods
     * @param \Reflector $reflector
     * @param bool       $noInherit Exclude inherited methods
     *
     * @return array
     */
    protected function getMethods($showAll, \Reflector $reflector, $noInherit = false)
    {
        $className = $reflector->getName();

        $methods = [];
        foreach ($reflector->getMethods() as $name => $method) {
            if ($noInh