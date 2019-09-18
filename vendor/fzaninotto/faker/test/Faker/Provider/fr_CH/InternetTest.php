<?php

/*
 Copyright (c) 2009 hamcrest.org
 */

/**
 * Controls the process of extracting @factory doctags
 * and generating factory method files.
 *
 * Uses File_Iterator to scan for PHP files.
 */
class FactoryGenerator
{
    /**
     * Path to the Hamcrest PHP files to process.
     *
     * @var string
     */
    private $path;

    /**
     * @var array of FactoryFile
     */
    private $factoryFiles;

    public function __construct($path)
    {
        $this->path = $path;
        $this->factoryFiles = array();
    }

    public function addFactoryFile(FactoryFile $factoryFile)
    {
        $this->factoryFiles[] = $factoryFile;
    }

    public function generate()
    {
        $classes = $this->getClassesWithFactoryMethods();
     