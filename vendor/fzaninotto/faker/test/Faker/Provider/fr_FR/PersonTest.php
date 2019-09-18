<?php

/*
 Copyright (c) 2009 hamcrest.org
 */

class FactoryParameter
{
    /**
     * @var FactoryMethod
     */
    private $method;

    /**
     * @var ReflectionParameter
     */
    private $reflector;

    public function __construct(FactoryMethod $method, ReflectionParameter $reflector)
    {
        $this->method = $method;
        $this->reflector = $reflector;
    }

    public function getDeclaration()
    {
        if ($this->reflector->isArray()) {
            $code = 'array ';
        } else {
            $class = $this->reflector->getClass();
            if ($class !== null) {
                $code = '\\' . $class->name . ' ';
            } else {
                $code = '';
            }
        }
        $code .= '$' . $this->reflector->name;
        if ($this->reflector->isOptional()) {
            $default = $this->reflector->getD