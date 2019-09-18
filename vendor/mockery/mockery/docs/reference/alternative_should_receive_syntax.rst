f (count($methodNames) === 0) {
            return new HigherOrderMessage($this, "shouldNotReceive");
        }

        $expectation = call_user_func_array(array($this, 'shouldReceive'), $methodNames);
        $expectation->never();
        return $expectation;
    }

    /**
     * Allows additional methods to be mocked that do not explicitly exist on mocked class
     * @param String $method name of the method to be mocked
     * @return Mock
     */
    public function shouldAllowMockingMethod($method)
    {
        $this->_mockery_mockableMethods[] = $method;
        return $this;
    }

    /**
     * Set mock to ignore unexpected methods and return Undefined class
     * @param mixed $returnValue the default return value for calls to missing functions on this mock
     * @return Mock
     */
    public function shouldIgnoreMissing($returnValue = null)
    {
        $this->_mockery_ignoreMissing = true;
        $this->_mockery_defaultReturnValue = $returnValue;
        return $this;
    }

    public function asUndefined()
    {
        $this->_mockery_ignoreMissing = true;
        $this->_mockery_defaultReturnValue = new \Mockery\Undefined;
        return $this;
    }

    /**
     * @return Mock
     */
    public function shouldAllowMockingProtectedMethods()
    {
        if (!\Mockery::getConfiguration()->mockingNonExistentMethodsAllowed()) {
            foreach ($this->mockery_getMethods() as $method) {
                if ($method->isProtected()) {
                    $this->_mockery_mockableMethods[] = $method->getName();
                }
            }
        }

        $this->_mockery_allowMockingProtectedMethods = true;
        return $this;
    }


    /**
     * Set mock to defer unexpected methods to it's parent
     *
     * This is particularly useless for this class, as it doesn't have a parent,
     * but included for completeness
     *
     * @deprecated 2.0.0 Please use makePartial() instead
     *
     * @return Mock
     */
    public function shouldDeferMissing()
    {
        return $this->makePartial();
    }

    /**
     * Set mock to defer unexpected methods to it's parent
     *
     * It was an alias for shouldDeferMissing(), which will be removed
     * in 2.0.0.
     *
     * @return Mock
     */
    public function makePartial()
    {
        $this->_mockery_deferMissing = true;
        return $this;
