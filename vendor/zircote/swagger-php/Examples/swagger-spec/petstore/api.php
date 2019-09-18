<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Processors;

use Swagger\Annotations\Definition;
use Swagger\Annotations\Items;
use Swagger\Context;
use Swagger\Analysis;

/**
 * Use the property context to extract useful information and inject that into the annotation.
 */
class AugmentProperties
{
    public static $types = [
        'array' => 'array',
        'byte' => ['string', 'byte'],
        'boolean' => 'boolean',
        'bool' => 'boolean',
        'int' => 'integer',
        'integer' => 'integer',
        'long' => ['integer', 'long'],
        'float' => ['number', 'float'],
        'double' => ['number', 'double'],
        'string' => 'string',
        'date' => ['string', 'date'],
        'datetime' => ['string', 'date-time'],
        '\datetime' => ['str