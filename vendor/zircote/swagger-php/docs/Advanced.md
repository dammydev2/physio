<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

use \Swagger\Logger;

/**
 * @Annotation
 * Describes a single operation parameter.
 *
 * A Swagger "Parameter Object": https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#parameterObject
 */
class Parameter extends AbstractAnnotation
{
    /**
     * $ref See http://json-schema.org/latest/json-schema-core.html#rfc.section.7
     * @var string
     */
    public $ref;

    /**
     * The key into Swagger->parameters or Path->parameters array.
     * @var string
     */
    public $parameter;

    /**
     * The name of the parameter. Parameter names are case sensitive. If in is "path", the name field MUST correspond to the associated path segment from the path field in the Paths Object. See Path Templating for further information. For all oth