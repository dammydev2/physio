  } else {
                return new Name($name);
            }
        }

        if ($allowExpr) {
            if ($name instanceof Expr) {
                return $name;
            }
            throw new \LogicException(
                'Name must be a string or an instance of Node\Name or Node\Expr'
            );
        } else {
            throw new \LogicException('Name must be a string or an instance of Node\Name');
        }
    }

    /**
     * Normalizes a type: Converts plain-text type names into proper AST representation.
     *
     * In particular, builtin types become Identifiers, custom types become Names and nullables
     * are wrapped in NullableType nodes.
     *
     * @param string|Name|Identifier|NullableType $type The type to normalize
     *
     * @return Name|Identifier|NullableType The normalized type
     */
    public static function normalizeType($type) {
        if (!is_string($type)) {
            if (!$type instanceof Name && !$type instanceof Identifier
                    && !$type instanceof NullableType) {
                throw new \LogicException(
                    'Type must be a string, or an instance of Name, Identifier or NullableType');
            }
            return $type;
        }

        $nullable = false;
        if (strlen($type) > 0 && $type[0] === '?') {
            $nullable = true;
            $type = substr($type, 1);
        }

        $builtinTypes = [
            'array', 'callable', 'string', 'int', 'float', 'bool', 'iterable', 'void', 'object'
        ];

        $lowerType = strtolower($type);
        if (in_array($lowerType, $builtinTypes)) {
            $type = new Identifier($lowerType);
        } else {
            $type = self::normalizeName($type);
        }

        if ($nullable && (string) $type === 'void') {
            throw new \LogicException('void type cannot be nullable');
        }

        return $nullable ? new Node\NullableType($type) : $type;
    }

    /**
     * Normalizes a value: Converts nulls, booleans, integers,
     * floats, strings and arrays into their respective nodes
     *
     * @param Node\Expr|bool|null|int|float|string|array $value The value to normalize
     *
     * @return Expr The normalized value
     */
    public static function normalizeValue($value) : Expr {
        if ($value instanceof Node\Expr) {
            return $value;
        } elseif (is_null($value)) {
            return new Expr\ConstFetch(
                new Name('null')
            );
        } elseif (is_bool($value)) {
            return new Expr\ConstFetch(
                new Name($value ? 'true' : 'false')
            );
        } elseif (is_int($value)) {
            return new Scalar\LNumber($value);
        } elseif (is_float($value)) {
            return new Scalar\DNumber($value);
        } elseif (is_string($value)) {
            return new Scalar\String_($value);
        } elseif (is_array($value)) {
            $items = [];
            $lastKey = -1;
            foreach ($value as $itemKey => $itemValue) {
                // for consecutive, numeric keys don't generate keys
                if (null !== $lastKey && ++$lastKey === $itemKey) {
                    $items[] = new Expr\ArrayItem(
                        self::normalizeValue($itemValue)
                    );
                } else {
                    $lastKey = null;
                    $items[] = new Expr\ArrayItem(
                        self::normalizeValue($itemValue)