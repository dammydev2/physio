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

use Psy\Formatter\SignatureFormatter;
use Psy\Input\FilterOptions;
use Psy\Util\Mirror;
use Psy\VarDumper\Presenter;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Abstract Enumerator class.
 */
abstract class Enumerator
{
    // Output styles
    const IS_PUBLIC    = 'public';
    const IS_PROTECTED = 'protected';
    const IS_PRIVATE   = 'private';
    const IS_GLOBAL    = 'global';
    const IS_CONSTANT  = 'const';
    const IS_CLASS     = 'class';
    const IS_FUNCTION  = 'function';

    private $filter;
    private $presenter;

    /**
     * Enumerator constructor.
     *
     * @param Presenter $presenter
     */
    public function 