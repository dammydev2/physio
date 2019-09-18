<?php
/**
 * @license Apache 2.0
 */

namespace Swagger\Processors;

use Swagger\Analysis;
use Swagger\Annotations\Operation;
use Swagger\Annotations\Path;
use Swagger\Annotations\Property;
use Swagger\Annotations\Response;
use Swagger\Annotations\Schema;
use Swagger\Logger;

/**
 * Copy the annotated properties from parent classes;
 */
class HandleReferences
{
    /** @var array The allowed imports in order of import */
    private $import_in_order = [
        'parameter' => 'parameters',
        'definition' => 'definitions',
        'response' => 'responses'
    ];

    private $references = [];
    private $head_references = [];

    public function __invoke(Analysis $analysis)
    {
        $this->getAllImports($analysis);
        $this->mapReferences();
        $this->importReferences();
    }

    /**
     * Gets all the possible importable objects and adds them to the lists.
     *
     * @param Analysis $analysis
     */
    private function getAllImports(Analysis $analysis)
    {
        // for all importable content
        foreach ($this->import_in_order as $propertyName => $importName) {
            // initialise the import name
            $this->references[$importName] = [];
            $this->head_references[$importName] = [];

            if (!is_null($analysis->swagger->$importName)) {
                /** @var Response $item */
                foreach ($analysis->swagger->$importName as $item) {
                    //if that identified value exists, or if the name isn't set then give blank id
                    if (!is_null($item->$propertyName) && isset($this->references[$importName][$item->$propertyName])) {
                        Logger::notice("$propertyName is already defined for object \"" . get_class($item) . '" in ' . $item->_context);
                    } else {
                        $this->references[$importName][$item->$propertyName] = $this->link($item);
//                        Logger::notice("$propertyName is NULL on object \"" . get_class($item) . '" in ' . $item->_context);
                    }
                }
            }
        }

        // All of the paths in the swagger, we need to iterate across
        if (!is_null($analysis->swagger->paths)) {
            /** @var Path $path */
            foreach ($analysis->swagger->paths as $path) {
                foreach ($path as $propertyName => $value) {
                    if ($value instanceof Operation && !is_null($value->responses)) {
                        //load each importable item if it is set
                        $this->loadResponses($value);
                        $this->loadParameters($value);
                        $this->loadSchemas($value);
                    }
                }
            }
        }
    }

    /**
     * Creates the Linked list array item.
     *
     * @param $response
     * @return array
     */
    private function link($response)
    {
        return [null, $response, []];
    }

    /**
     * Loads all the responses into the mapping
     *
     * @param Operation $operation
     */
    private function loadResponses(Operation $operation)
    {
        if (!is_null($operation->responses)) {
            foreach ($operation->responses as $item) {
                if ($this->checkSyntax($item->ref)) {
                    $this->references[$this->import_in_order['response']][] = $this->link($item);
                }
            }
        }
    }

    /**
     * Checks the syntax of the string to make sure it starts with a $
     *
     * @param $string
     * @return int
     */
    private function checkSyntax($string)
    {
        return isset($string) && preg_match('/^\$/', $string);
    }

    /**
     * Loads all the parameters into the mapping
     *
     * @param Operation $operation
     */
    private function loadParameters(Operation $operation)
    {
        if (!is_null($operation->parameters)) {
            foreach ($operation->parameters as $item) {
                if ($this->checkSyntax($item->ref)) {
                    $this->references[$this->import_in_order['parameter']][] = $this->link