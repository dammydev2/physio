            return $this->resolveTypedArray($type, $context);
            case $this->isFqsen($type):
                return $this->resolveTypedObject($type);
            case $this->isPartialStructuralElementName($type):
                return $this->resolveTypedObject($type, $context);
            // @codeCoverageIgnoreStart
            default:
                // I haven't got the foggiest how the logic would come here but added this as a defense.
                throw new \RuntimeException(
                    'Unable to resolve type "' . $type . '", there is no known method to resolve it'
                );
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Adds a keyword to the list of Keywords and associates it with a specific Value Object.
     *
     * @param string $keyword
     * @param string $typeClassName
     *
     * @return void
     */
    public function addKeyword($keyword, $typeClassName)
    {
        if (!class_exists($typeClassName)) {
            throw new \InvalidArgumentException(
                'The Value Object that needs to be created with a keyword "' . $keyword . '" must be an existing class'
                . ' but we could not find the class ' . $typeClassName
            );
        }

        if (!in_array(Type::class, class_implements($typeClassName))) {
            throw new \InvalidArgumentException(
                'The class "' . $typeClassName . '" must implement the interface "phpDocumentor\Reflection\Type"'
            );
        }

        $this->keywords[$keyword] = $typeClassName;
    }

    /**
     * Detects whether the given type represents an array.
     *
     * @param string $type A relative or absolute type as defined in the phpDocumentor documentation.
     *
     * @return bool
     */
    private function isTypedArray($type)
    {
        return substr($type, -2) === self::OPERATOR_ARRAY;
    }

    /**
     * Detects whether the given type represents a PHPDoc keyword.
     *
     * @param string $type A relative or absolute type as defined in the phpDocumentor documentation.
     *
     * @return bool
     */
    private function isKeyword($type)
    {
        return in_array(strtolower($type), array_keys($this->keywords), true);
    }

    /**
     * Detects whether the given type represents a relative structural element name.
     *
     * @param string $type A relative or absolute type as defined in the phpDocumentor documentation.
     *
     * @return bool
     */
    private function isPartialStructuralElementName($type)
    {
        return ($type[0] !== self::OPERATOR_NAMESPACE) && !$this->isKeyword($type);
    }

    /**
     * Tests whether the given type is a Fully Qualified Structural Element Name.
     *
     * @param string $type
     *
     * @return bool
     */
    private function isFqsen($type)
    {
        return strpos($type, self::OPERATOR_NAMESPACE) === 0;
    }

    /**
     * Tests whether the given type is a compound type (i.e. `string|int`).
     *
     * @param string $type
     *
     * @return bool
     */
    private function isCompoundType($type)
    {
        return strpos($type, '|') !== false;
    }

    /**
     * Test whether the given type is a nullable type (i.e. `?string`)
     *
     * @param string $type
     *
     * @return bool
     */
    private function isNullableType($type)
    {
        return $type[0] === '?';
    }

    /**
     * Resolves the given typed array string (i.e. `string[]`) into an Array object with the right types set.
     *
     * @param string $type
     * @param Context $context
     *
     * @return Array_
     */
    private function resolveTypedArray($type, Context $context)
    {
        return new Array_($this->resolve(substr($type, 0, -2), $context));
 