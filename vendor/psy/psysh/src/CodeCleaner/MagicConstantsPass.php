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

use Psy\VarDumper\Presenter;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Interface Enumerator class.
 *
 * @deprecated Nothing should use this anymore
 */
class InterfaceEnumerator extends Enumerator
{
    public function __construct(Presenter $presenter)
    {
        @\trigger_error('InterfaceEnumerator is no longer used', E_USER_DEPRECATED);
        parent::__construct($presenter);
    }

    /**
     * {@inheritdoc}
     */
    protected function listItems(InputInterface $input, \Reflector $reflector = null, $target = null)
    {
        // only list interfaces when no Reflector is present.
        //
        // @todo make a NamespaceReflector and pass that in for commands like:
        //
        //     ls --interfaces Foo
        //
        // ... for listing interfaces in the Foo nam