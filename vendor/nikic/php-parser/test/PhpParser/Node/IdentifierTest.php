<?php
/* ===========================================================================
 * Copyright (c) 2018 Zindex Software
 *
 * Licensed under the MIT License
 * =========================================================================== */

namespace Opis\Closure;

use Closure;
use Serializable;
use SplObjectStorage;
use ReflectionObject;

/**
 * Provides a wrapper for serialization of closures
 */
class SerializableClosure implements Serializable
{
    /**
     * @var Closure Wrapped closure
     *
     * @see \Opis\Closure\SerializableClosure::getClosure()
     */
    protected $closure;

    /**
     * @var ReflectionClosure A reflection instance for closure
     *
     * @see \Opis\Closure\SerializableClosure::getReflector()
     */
    protected $reflector;

    /**
     * @var mixed Used at deserialization to hold variables
     *