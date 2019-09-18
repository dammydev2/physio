<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 *
 * A Swagger "Response Object": https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#responseObject
 */
class Response extends AbstractAnnotation
{
    /**
     * $ref See http://json-schema.org/latest/json-schema-core.html#rfc.section.7
     * @var string
     */
    public $ref;

    /**
     * The key into Operations->responses array.
     *
     * @var string a HTTP Status Code or "default"
     */
    public $response;

    /**
     * A short description of the response. GFM syntax can be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * A definition of the response structure. It can be a primitive, an array or an obje