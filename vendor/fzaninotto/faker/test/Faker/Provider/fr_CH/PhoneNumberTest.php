<?php

/*
 Copyright (c) 2009 hamcrest.org
 */

/**
 * Represents a single static factory method from a {@link Matcher} class.
 *
 * @todo Search method in file contents for func_get_args() to replace factoryVarArgs.
 */
class FactoryMethod
{
    /**
     * @var FactoryClass
     */
    private $class;

    /**
     * @var ReflectionMethod
     */
    private $reflector;

    /**
     * @var array of string
     */
    private $comment;

    /**
     * @var bool
     */
    private $isVarArgs;

    /**
     * @var array of FactoryCall
     */
    private $calls;

    /**
     * @var array FactoryParameter
     */
    private $parameters;

    public function __construct(FactoryClass $class, ReflectionMethod $reflector)
    {
    