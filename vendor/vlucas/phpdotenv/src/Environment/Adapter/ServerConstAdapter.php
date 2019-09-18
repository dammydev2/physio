<?php

/**
 * @license Apache 2.0
 */

namespace Swagger;

use Closure;
use Exception;
use SplObjectStorage;
use stdClass;
use Swagger\Annotations\AbstractAnnotation;
use Swagger\Annotations\Swagger;
use Swagger\Processors\AugmentDefinitions;
use Swagger\Processors\AugmentOperations;
use Swagger\Processors\AugmentParameters;
use Swagger\Processors\AugmentProperties;
use Swagger\Processors\BuildPaths;
use Swagger\Processors\CleanUnmerged;
use Swagger\Processors\HandleReferences;
use Swagger\Processors\InheritProperties;
use Swagger\Processors\MergeIntoSwagger;

/**
 * Result of the analyser which pretends to be an array of annotations, but also contains detected classes and helper functions for the processors.
 */
class Analysis
{
    /**
     * @var SplObjectStorage
     */
    public $annotations;

    /**
     * Class definitions
     * @var array
     */
    public $classes = [];

    /**
     * The target Swagger annotation.
     * @var Swagger
     */
    public $swagger;

    /**
     * Registry for the post-processing operations.
     * @va