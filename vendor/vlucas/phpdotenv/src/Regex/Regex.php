<?php

/**
 * @license Apache 2.0
 */

namespace Swagger;

/**
 * Context
 *
 * The context in which the annotation is parsed.
 * It includes useful metadata which the Processors can use to augment the annotations.
 *
 * Context hierarchy:
 * - parseContext
 *   |- docBlockContext
 *   |- classContext
 *      |- docBlockContext
 *      |- propertyContext
 *      |- methodContext
 *
 * @property string $comment  The PHP DocComment
 * @property string $filename
 * @property int $line
 * @property int $character
 *
 * @property string $namespace
 * @property array $uses
 * @property string $class
 * @property string $extends
 * @property string $method
 * @property string $property
 * @property Annotations\AbstractAnnotation[] $annotations
 */
class Context
{
    /**
     * Prototypical inheritance for properties.
     * @var Context
     */
    private $_parent;

    /**
     * @param array $properties new properties for this context.
     * @param Context $parent The parent context
     */
    public function __construct($properties = [], $parent = null)
    {
        foreach ($properties as $property => $value) {
            $this->$property = $value;
        }
        $this->_parent = $parent;
    }

    /**
     * Check if a property is set directly on this context and not its parent context.
     *
     * @param string $type Example: $c->is('method') or $c->is('class')
     * @return bool
     */
    public function is($type)
    {
        return property_exists($this, $type);
    }

    /**
     * Check if a property is NOT set directly on this context and but its parent context.
     *
     * @param string $type Example: $c->not('method') or $c->not('class')
     * @return bool
     */
    public function not($type)
    {
        return property_exists($this, $type) === false;
    }

    /**
     * Return the context containing the specified property.
     *
     * @param string $property
     * @return boolean|\Swagger\Context
     */
    public function with($property)
    {
        if (property_exists($this, $property)) {
            return $this;
        }
        if ($this->_parent) {
            return $this->_parent->with($property);
        }
        return false;
    }

    /**
     * @return \Swagger\Context
     */
    public function getRootContext()
    {
        if ($this->_parent) {
            return $this->