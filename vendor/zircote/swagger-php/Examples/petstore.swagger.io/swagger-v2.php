<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 * A Swagger "Security Scheme Object": https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#securitySchemeObject
 */
class SecurityScheme extends AbstractAnnotation
{
    /**
     * The key into Swagger->securityDefinitions array.
     * @var string
     */
    public $securityDefinition;

    /**
     * The type of the security scheme.
     * @var string
     */
    public $type;

    /**
     * A short description for security scheme.
     * @var string
     */
    public $description;

    /**
     * The name of the header or query parameter to be used.
     * @var string
     */
    public $name;

    /**
     * Required The location of the API key.
     * @var string
     */
    public $in;

    /**
     * The flow used by the OAuth2 security scheme.
     * @var
     */
    public $flow;

    /