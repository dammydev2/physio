->getClassMethods($this->type),
                $this->methodsExcept
            )
        );

        return $this;
    }

    /**
     * Specifies the arguments for the constructor.
     *
     * @return MockBuilder
     */
    public function setConstructorArgs(array $args)
    {
        $this->constructorArgs = $args;

        return $this;
    }

    /**
     * Specifies the name for the mock class.
     *
     * @param string $name
     *
     * @return MockBuilder
     */
    public function setMockClassName($name)
    {
        $this->mockClassName = $name;

        return $this;
    }

    /**
     * Disables the invocation of the original constructor.
     *
     * @return MockBuilder
     */
    public function disableOriginalConstructor()
    {
        $this->originalConstructor = false;

        return $this;
    }

    /**
     * Enables the invocation of the original constructor.
     *
     * @return MockBuilder
     */
    public function enableOriginalConstructor()
    {
        $this->originalConstructor = true;

        return $this;
    }

    /**
     * Disables the invocation of the original clone constructor.
     *
     * @return MockBuilder
     */
    public function disableOriginalClone()
    {
        $this->originalClone = false;

        return $this;
    }

    /**
     * Enables the invocation of the original clone constructor.
     *
     * @return MockBuilder
     */
    public function enableOriginalClone()
    {
        $this->originalClone = true;

        return $this;
    }

    /**
     * Disables the use of class autoloading while creating the mock object.
     *
     * @return MockBuilder
     */
    public function disableAutoload()
    {
        $this->autoload = false;

        return $this;
    }

    /**
     * Enables the use of class autoloading while creating the mock object.
     *
     * @return MockBuilder
     */
    public function enableAutoload()
    {
        $this->autoload = true;

        retur