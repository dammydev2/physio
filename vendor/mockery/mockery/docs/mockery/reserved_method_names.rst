  */
    public function mockery_init(\Mockery\Container $container = null, $partialObject = null)
    {
        if (is_null($container)) {
            $container = new \Mockery\Container;
        }
        $this->_mockery_container = $container;
        if (!is_null($partialObject)) {
            $this->_mockery_partial = $partialObject;
        }

        if (!\Mockery::getConfiguration()->mockingNonExistentMethodsAllowed()) {
            foreach ($this->mockery_getMethods() as $method) {
                if ($method->isPublic()) {
                    $this->_mockery_mockableMethods[] = $method->getName();
                }
            }
        }
    }

    /**
     * Set expected method calls
     *
     * @param mixed ...$methodNames one or many methods that are expected to be cal