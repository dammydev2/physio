
    {
        $endPoint = &$this->getEndPoint();
        $endPoint['lookupType'] = self::TYPE_VALUE;
        $endPoint['value'] = $value;

        return $this;
    }

    /**
     * Specify the previously registered item as an alias of another item.
     *
     * @param string $lookup
     *
     * @return $this
     */
    public function asAliasOf($lookup)
    {
        $endPoint = &$this->getEndPoint();
        $endPoint['lookupType'] = self::TYPE_ALIAS;
        $endPoint['ref'] = $lookup;

        return $this;
    }

    /**
     * Specify the previously registered item as a new instance of $className.
     *
     * {@link register()} must be called before this will work.
     * Any arguments can be set with {@link withDependencies()},
     * {@link addConstructorValue()} or {@link addConstructorLookup()}.
     *
     * @see withDependencies(), addConstructorValue(), addConstructorLookup()
     *
     * @param string $className
     *
     * @return $this
     */
    public function asNewInstanceOf($className)
    {
        $endPoint = &$this->getEndPoint();
        $endPoint['lookupType'] = self::TYPE_INSTANCE;
        $endPoint['className'] = $className;

        return $this;
    }

    /**
     * Specify the previously registered item as a shared instance of $className.
     *
     * {@link register()} must be called before this will work.
     *
     * @param string $className
     *
     * @return $this
     */
    public function asSharedInstanceOf($className)
    {
        $endPoint = &$this->getEndPoint();
        $endPoint['lookupType'] = self::TYPE_SHARED;
        $endPoint['className'] = $className;

        return $this;
    }

    /**
     * Specify the previously registered item as array of dependencies.
     *
     * {@link register()} must be called before this will work.
     *
     * @return $this
     */
    public function asArray()
    {
        $endPoint = &$this->getEndPoint();
        $endPoint['lookupType'] = self::TYPE_ARRAY;

        return $this;
    }

    /**
     * Specify a list of injected dependencies for the previously registered item.
     *
     * This method takes an array of lookup names.
     *
     * @see addConstructorValue(), addConstructorLookup()
     *
     * @return $this
     */
    public function withDependencies(array $lookups)
    {
        $endPoint = &$thi