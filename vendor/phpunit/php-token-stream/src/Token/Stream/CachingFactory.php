AttributeName($attributeName)) {
            throw InvalidArgumentHelper::factory(2, 'valid attribute name');
        }

        if (\is_string($classOrObject)) {
            if (!\class_exists($classOrObject)) {
                throw InvalidArgumentHelper::factory(
                    1,
                    'class name'
                );
            }

            return static::getStaticAttribute(
                $classOrObject,
                $attributeName
            );
        }

        if (\is_object($classOrObject)) {
            return static::getObjectAttribute(
                $classOrObject,
                $attributeName
            );
        }

        throw InvalidArgumentHelper::factory(
            1,
            'class name or object'
        );
    }

    /**
     * Returns the value of a static attribute.
     * This also works for attributes that are declared protected or private.
     *
     * @throws Exception
     * @thr