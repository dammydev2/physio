<?php

namespace Prettus\Repository\Generators;

use Prettus\Repository\Generators\Migrations\SchemaParser;

/**
 * Class RepositoryInterfaceGenerator
 * @package Prettus\Repository\Generators
 */
class RepositoryInterfaceGenerator extends Generator
{

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'repository/interface';

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        return parent::getRootNamespace() . parent::getConfigGeneratorClassPath($this->getPathConfigNode());
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'interfaces';
    }

    /**
