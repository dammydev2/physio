<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 * Describes the operations available on a single path.
 * A Path Item may be empty, due to ACL constraints.
 * The path itself is still exposed to the documentation viewer but they will not know which operations and parameters are available.
 *
 * A Swagger "Path Item Object": https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#path-item-object-
 */
class Path extends AbstractAnnotation
{
    /**
     * $ref See http://json-schema.org/latest/json-schema-core.html#rfc.section.7
     * @var string
     */
    public $ref;

    /**
     * key in the Swagger "Paths Object" for this path.
     * @var string
     */
    public $path;

    /**
     * A definition of a GET operation on this path.
     * @var Get
     */
    public $get;

    /**
     * A definition of a PUT operation on this path.
     * @var Put
     */
    public $put;

    /**
     * A definition of a POST operation on this path.
     * @var Post
     */
    public $post;

    /**
     * A definition of a DELETE operation on this path.
     * @var Delete
     */
    public