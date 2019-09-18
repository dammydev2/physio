<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

use Swagger\Logger;

/**
 * @Annotation
 * The definition of input and output data types.
 * These types can be objects, but also primitives and arrays.
 * This object is based on the [JSON Schema Specification](http://json-schema.org) and uses a predefined subset of it.
 * On top of this subset, there are extensions provided by this specification to allow for more complete documentation.
 *
 * A Swagger "Schema Object": https://github.com/swagger-api/swagger-spec/blob/master/versions/2.0.md#schemaObject
 * JSON Schema: http://json-schema.org/latest/json-schema-validation.html
 */
class Schema extends AbstractAnnotation
{
    /**
     * $ref See http://json-schema.org/latest/json-schema-core.html#rfc.section.7
     * @var string
     */
    public $ref;

    /**
     * Can be used to decorate a user interface with information about the data produced by this user interface. preferrably be short.
     * @var string
     */
    public $title;

    /**
     * A description will provide explanation about the purpose of the instance described by this schema.
     * @var string
     */
    public $description;

    /**
     * An object instance is valid against "maxProperties" if its number of properties is less than, or equal to, the value of this property.
     * @var integer
     */
    public $maxProperties;

    /**
     * An object instance is valid against "minProperties" if its number of properties is greater than, or equal to, the value of this property.
     * @var integer
     */
    public $minProperties;

    /**
     * An object instance is valid against this property if its property set contains all elements in this property's array value.
     * @var string[]
     */
    public $required;

    /**
     * @var Property[]
     */
    public $properties;

    /**
     * The type of the schema/property. The value MUST be one of "string", "number", "integer", "boolean", "array" or "object".
     * @var string
     */
    public $type;

    /**
     * The extending format for the previously mentioned type. See Data Type Formats for further details.
     * @var string
     */
    public $format;

    /**
     * Required if type is "array". Describes the type of items in the array.
     * @var Items
     */
    public $items;

    /**
     * @var string Determines the format of the array if type array is used. Possible values are: csv - comma separated values foo,bar. ssv - space separated values foo bar. tsv - tab separated values foo\tbar. pipes - pipe separated values foo|bar. multi - corresponds to multiple parameter instances instead of multipl