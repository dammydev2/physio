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
 * Constant Enumerator class.
 */
class ConstantEnumerator extends Enumerator
{
    /**
     * {@inheritdoc}
     */
    protected function listItems(InputInterface $input, \Reflector $reflector = null, $target = null)
    {
        // only list constants when no Reflector is present.
        //
        // @todo make a NamespaceReflector and pass that in for commands like:
        //
        //     ls --constants Foo
        //
        // ... for listing constants in the Foo namespace
        if ($reflector !== null || $target !== null) {
            return;
        }

        // only list constants if we are specifically asked
        if (!$input->getOption('constants')) {
            return;
        }

        $user     = $input->getOption('user');
        $internal = $input->getOption('internal');
        $category = $input->getOption('category');

        $ret = [];

        if ($user) {
            $ret['User Constants'] = $this->getConstants('user');
        }

        if ($internal) {
            $re