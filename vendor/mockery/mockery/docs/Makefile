der->setName($name);
                continue;
            } elseif (is_string($arg) && substr($arg, strlen($arg)-1, 1) == ']') {
                $parts = explode('[', $arg);
                if (!class_exists($parts[0], true) && !interface_exists($parts[0], true)) {
                    throw new \Mockery\Exception('Can only create a partial mock from'
                    . ' an existing class or interface');
                }
                $class = $parts[0];
                $parts[1] = str_replace(' ', '', $parts[1]);
                $partialMethods = explode(',', strtolower(rtrim($parts[1], ']')));
                $builder->addTarget($class);
                $builder->setWhiteListedMethods($partialMethods);
                array_shift($args);
                continue;
            } elseif (is_string($arg) && (class_exists($arg, true) || interface_exists($arg, true) || trait_exists($arg, true))) {
                $class = array_shift($args);
                $builder->addTarget($class);
                continue;
            } elseif (is_string($arg) && !\Mockery::getConfiguration()->mockingNonExistentMethodsAllowed() && (!class_exists($arg, true) && !interface_exists($arg, true))) {
                throw new \Mockery\Exception("Mockery can't find '$arg' so can't mock it");
            } elseif (is_string($arg)) {
                if (!$this->isValidClassName($arg)) {
                    throw new \Mockery\Exception('Class name contains invalid characters');
                }
                $class = array_shift($args);
                $builder->addTarget($class);
                continue;
            } elseif (is_object($arg)) {
                $partial = array_shift($args);
                $builder->addTarget($partial);
                continue;
            } elseif (is_array($arg) && !empty($arg) && array_keys($arg) !== range(0, count($arg) - 1)) {
                // if associative array
                if (array_key_exists(self::BLOCKS, $arg)) {
                    $blocks = $arg[self::BLOCKS];
                }
                unset($arg[self::BLOCKS]);
                $quickdefs = array_shift($args);
                continue;
            } elseif (is_array($arg)) {
                $constructorArgs = array_shift($args);
                continue;
            }

            throw new \Mockery\Exception(
                'Unable to parse arguments sent to '
                . get_class($this) . '::mock()'
            );
        }

        $builder->addBlackListedMethods($blocks);

        if (defined('HHVM_VERSION')
            && ($class === 'Exception' || is_subclass_of($class, 'Exception'))) {
            $builder->addBlackListedMethod("setTraceOptions");
            $builder->addBlackListedMethod("getTraceOptions");
        }

        if (!is_null($constructorArgs)) {
            $builder->addBlackListedMethod("__construct"); // we need to pass through
        } else {
            $builder->setMockOriginalDestructor(true);
        }

        if (!empty($partialMethods) && $constructorArgs === null) {
            $constructorArgs = array();
        }

        $config = $builder->getMockConfiguration();

        $this->checkForNamedMockClashes($config);

        $def = $this->getGenerator()->generate($config);

        if (class_exists($def->getClassName(), $attemptAutoload = false)) {
            $rfc = new \ReflectionClass($def->getClassName());
            if (!$rfc->implementsInterface("Mockery\MockInterface")) {
                throw new \Mockery\Exception\RuntimeException("Could not load mock {$def->getClassName()}, class already exists");
            }
        }

        $this->getLoader()->load($def);

        $mock = $this->_getInstance($def->getClassName(), $constructorArgs);
        $mock->mockery_init($this, $config->getTargetObject());

        if (!empty($quickdefs)) {
            $mock->shouldReceive($quickdefs)->byDefault();
        }
        if (!empty($expectationClosure)) {
            $expectationClosure($mock);
        }
        $this->rememberMock($mock);
        return $mock;
    }

    public function instanceMock()
    {
    }

    public function getLoader()
    {
        return $this->_loader;
    }

    public function getGenerator()
    {
        return $this->_generator;
    }

    /**
     * @param string $method
     * @param string $parent
     * @return string|null
     */
    public function getKeyOfDemeterMockFor($method, $parent)
    {
        $keys = array_keys($this->_mocks);
        $match = preg_grep("/__demeter_" . md5($parent) . "_{$method}$/", $keys);
        if (count($match) == 1) {
            $res = array_values($match);
            if (count($res) > 0) {
                return $res[0];
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function getMocks()
    {
        return $this->_mocks;
    }

    /**
     *  Tear down tasks for this container
     *
     * @throws \Exception
     * @return void
     */
    public function mockery_teardown()
    {
        try {
            $this->mockery_verify();
        } catch (\Exception $e) {
            $this->mockery_close();
            throw $e;
        }
    }

    /**
     * Verify the container mocks
     *
     * @return void
     */
    public function mockery_verify()
    {
        foreach ($this->_mocks as $mock) {
            $mock->mockery_verify();
        }
    }

    /**
     * Retrieves all exceptions thrown by mocks
     *
     * @return array
     */
    public function mockery_thrownExceptions()
    {
        $e = [];

        foreach ($this->_mocks as $mock) {
            $e = array_merge($e, $mock->mockery_thrownExceptions());
        }

        return $e;
    }

    /**
     * Reset the container to its original state
     *
     * @return void
     */
    public function mockery_close()
    {
        foreach ($this->_mocks as $mock) {
            $mock->mockery_teardown();
        }
        $this->_mocks = array();
    }

    /**
     * Fetch the next available allocation order number
     *
     * @return int
     */
    public function mockery_allocateOrder()
    {
        $this->_allocatedOrder += 1;
        return $this->_allocatedOrder;
    }

    /**
     * Set ordering for a group
     *
     * @param mixed $group
     * @param int $order
     */
    public function mockery_setGroup($group, $order)
    {
        $this->_groups[$group] = $order;
    }

    /**
     * Fetch array of ordered groups
     *
     * @return array
     */
    public function mockery_getGroups()
    {
        return $this->_groups;
    }

    /**
     * Set current ordered number
     *
     * @param int $order
     * @return int The current order number that was set
     */
    public function mockery_setCur