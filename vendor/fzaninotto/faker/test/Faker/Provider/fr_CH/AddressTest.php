<?php

/*
 Copyright (c) 2009 hamcrest.org
 */

abstract class FactoryFile
{
    /**
     * Hamcrest standard is two spaces for each level of indentation.
     *
     * @var string
     */
    const INDENT = '    ';

    private $indent;

    private $file;

    private $code;

    public function __construct($file, $indent)
    {
        $this->file = $file;
        $this->indent = $indent;
    }

    abstract public function addCall(FactoryCall $call);

    abstract public function build();

    public function addFileHeader()
    {
        $this->code = '';
        $this->addPart('file_header');
    }

    public function addPart($name)
    {
        $this->addCode($this->readPart($name));
    }

    public function addCode($code)
    {
        $this->code .= $code;
    }

    public function readPart($name)
    {
        return file_get_contents(__DIR__ . "/parts/$name.txt");
    }

    public function generateFactoryCall(FactoryCall $call)
    {
        $method = $call->getMethod();
        $code = $method->getComment($this->indent) . PHP_EOL;
        $code .= $this->generateDeclaration($call->getName(), $method);
        // $code .= $this->generateImport($method);
        $code .= $this->generateCall($method);
        $code .= $this->generateClosing();
        return $code;
    }

    public function generateDeclaration($name, FactoryMethod $method)
    {
        $code = $this->indent . $this->getDeclarationModifiers()
            . 'function ' . $name . '('
            . $this->generateDeclarationArguments($method)
            . ')' . PHP_EOL . $this->indent . '{' . PHP_EOL;
        return $code;
    }

    publi