<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Util;

/**
 * A docblock representation.
 *
 * Based on PHP-DocBlock-Parser by Paul Scott:
 *
 * {@link http://www.github.com/icio/PHP-DocBlock-Parser}
 *
 * @author Paul Scott <paul@duedil.com>
 * @author Justin Hileman <justin@justinhileman.info>
 */
class Docblock
{
    /**
     * Tags in the docblock that have a whitespace-delimited number of parameters
     * (such as `@param type var desc` and `@return type desc`) and the names of
     * those parameters.
     *
     * @var array
     */
    public static $vectors = [
        'throws' => ['type', 'desc'],
        'param'  => ['type', 'var', 'desc'],
        'return' => ['type', 'desc'],
    ];

    protected $reflector;

    /**
     * The description of the symbol.
  