<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy;

/**
 * The Shell execution context.
 *
 * This class encapsulates the current variables, most recent return value and
 * exception, and the current namespace.
 */
class Context
{
    private static $specialNames = ['_', '_e', '__out', '__psysh__', 'this'];

    // Whitelist a very limited number of command-scope magic variable names.
    // This might be a bad idea, but future me can sort it out.
    private static $commandScopeNames = [
        '__function', '__method', '__class', '__namespace', '__file', '__line', '__dir',
    ];

    private $scopeVariables = [];
    private $commandScopeVariables = [];
    private $returnValue;
    private $lastException;
    private $lastStdout;
    private $boundObject;
    private $boundClass;

    /**
     * Get a context variable.
     *
     * @throws InvalidArgumentException If the variable is not found in the current context
     *
     * @param string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        switch ($name) {
            case '_':
                ret