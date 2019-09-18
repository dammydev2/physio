<?php
namespace Prettus\Repository\Generators;

/**
 * Class TransformerGenerator
 * @package Prettus\Repository\Generators
 */

class TransformerGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'transformer/transformer';

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
        return 'transformers';
    }

    /**
     *