<?php
namespace Prettus\Repository\Generators;

use Prettus\Repository\Generators\Migrations\RulesParser;
use Prettus\Repository\Generators\Migrations\SchemaParser;

/**
 * Class ValidatorGenerator
 * @package Prettus\Repository\Generators
 */
class ValidatorGenerator extends Generator
{

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'validator/validator';

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
        return 'validators';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    pub