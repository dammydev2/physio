     return $this->_mockery_thrownExceptions;
    }

    /**
     * Tear down tasks for this mock
     *
     * @return void
     */
    public function mockery_teardown()
    {
    }

    /**
     * Fetch the next available allocation order number
     *
     * @return int
     */
    public function mockery_allocateOrder()
    {
        $this->_mockery_allocatedOrder += 1;
        return $this->_mockery_allocatedOrder;
    }

    /**
     * Set ordering for a group
     *
     * @param mixed $group
     * @param int $order
     */
    public function mockery_setGroup($group, $order)
    {
        $this->_mockery_groups[$group] = $order;
    }

    /**
     * Fetch array of ordered groups
     *
     * @return array
     */
    public function mockery_getGroups()
    {
        return $this->_mockery_groups;
    }

    /**
     * Set current ordered number
     *
     * @param int $order
     */
    public function mockery_setCurrentOrder($order)
    {
        $this->_mockery_currentOrder = $order;
        return $this->_mockery_currentOrder;
    }

    /**
     * Get current ordered number
     *
     * @return int
     */
    public function mockery_getCurrentOrder()
    {
        return $this->_mockery_currentOrder;
    }

    /**
     * Validate the current mock's ordering
     *
     * @param string $method
     * @param int $order
     * @throws \Mockery\Exception
     * @return void
     */
    public function mockery_validateOrder($method, $order)
    {
        if ($order < $this->_mockery_currentOrder) {
            $exception = new \Mockery\Exception\InvalidOrderException(
                'Method ' . __CLASS__ . '::' . $method . '()'
                . ' called out of order: expected order '
                . $order . ', was ' . $this->_mockery_currentOrder
            );
            $exception->setMock($this)
                ->setMethodName($method)
                ->setExpectedOrder($order)
                ->setActualOrder($this->_mockery_currentOrder);
            throw $exception;
        }
        $this->mockery_setCurrentOrder($order);
    }

    /**
     * Gets the count of expectations for this mock
     *
     * @return int
     */
    public function mockery_getExpectationCount()
    {
        $count = $this->_mockery_expectations_count;
        foreach ($this->_mockery_expectations as $director) {
            $count += $director->getExpectationCount();
        }
        return $count;
    }

    /**
     * Return the expectations director for the given method
     *
     * @var string $method
     * @return \Mockery\ExpectationDirector|null
     */
    public function mockery_setExpectationsFor($method, \Mockery\ExpectationDirector $director)
    {
        $this->_mockery_expectations[$method] = $director;
    }

    /**
     * Return the expectations director for the given method
     *
     * @var string $method
     * @return \Mockery\ExpectationDirector|null
     */
    public function mockery_getExpectationsFor($method)
    {
        if (isset($this->_mockery_expectations[$method])) {
            return $this->_mockery_expectations[$method];
        }
    }

    /**
     * Find an expectation matching the given method and arguments
     *
     * @var string $method
     * @var array $args
     * @return \Mockery\Expectation|null
     */
    public function mockery_findExpectation($method, array $args)
    {
        if (!isset($this->_mockery_expectations[$method])) {
            return null;
        }
        $director = $this->_mockery_expectations[$method];

        return $director->findExpectation($args);
    }

    /**
     * Return the container for this mock
     *
     * @return \Mockery\Container
     */
    public function mockery_getContainer()
    {
        return $this->_mockery_container;
    }

    /**
     * Return the name for this mock
     *
     * @return string
     */
    public function mockery_getName()
    {
        return __CLASS__;
    }

    /**
     * @return array
     */
    public function mockery_getMockableProperties()
    {
        return $this->_mockery_mockableProperties;
    }

    public function __isset($name)
    {
        if (false === stripos($name, '_mockery_') && method_exists(get_parent_class($this), '__isset')) {
            return parent::__isset($name);
        }

        return false;
    }

    public function mockery_getExpectations()
    {
        return $this->_mockery_expectations;
    }

    /**
     * Calls a parent class method and returns the result. Used in a passthru
     * expectation where a real return value is required while still taking
     * advantage of expectation matching and call count verification.
     *
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function mockery_callSubjectMethod($name, array $args)
    {
        return call_user_func_array('parent::' . $name, $args);
    }

    /**
     * @return string[]
     */
    public function mockery_getMockableMethods()
    {
        return $this->_mockery_mockableMethods;
    }

    /**
     * @return bool
     */
    public function mockery_isAnonymous()
    {
        $rfc = new \ReflectionClass($this);

        // HHVM has a Stringish interface
        $interfaces = array_filter($rfc->getInterfaces(), function ($i) {
            return $i->getName() !== "Stringish";
        });
        $onlyImplementsMock = 1 == count($interfaces);

        return (false === $rfc->getParentClass()) && $onlyImplementsMock;
    }

    public function __wakeup()
    {
        /**
         * This does not add __wakeup method support. It's a blind method and any
         * expected __wakeup work will NOT be performed. It merely cuts off
         * annoying errors where a __wakeup exists but is not essential when
         * mocking
         */
    }

    public function __destruct()
    {
        /**
         * Overrides real class destructor in case if class was created without original constructor
         */
    }

    public function mockery_getMethod($name)
    {
        foreach ($this->mockery_getMethods() as $method) {
            if ($method->getName() == $name) {
                return $method;
            }
        }

        return null;
    }

    /**
     * @param string $name Method name.
     *
     * @return mixed Generated return value based on the declared return value of the named method.
     */
    public function mockery_returnValueForMethod($name)
    {
        if (version_compare(PHP_VERSION, '7.0.0-dev') < 0) {
            return;
        }

        $rm = $this->mockery_getMethod($name);
        if (!$rm || !$rm->hasReturnType()) {
            return;
        }

        $returnType = $rm->getReturnType();

        // Default return value for methods with nullable type is null
        if ($returnType->allowsNull()) {
            return null;
        }

        $type = (string) $returnType;
        switch ($type) {
            case '':       return;
            case 'string': return '';
            case 'int':    return 0;
            case 'float':  return 0.0;
            case 'bool':   return false;
            case 'array':  return [];

            case 'callable':
            case 'Closure':
                return function () {
                };

            case 'Traversable':
            case 'Generator':
                // Remove eval() when minimum version >=5.5
                $generator = eval('return function () { yield; };');
                return $generator();

            case 'self':
                return \Mockery::mock($rm->getDeclaringClass()->getName());

            case 'void':
                return null;

            case 'object':
                if (version_compare(PHP_VERSION, '7.2.0-dev') >= 0) {
                    return \Mockery::mock();
                }

            default:
                return \Mockery::mock($type);
        }
    }

    public function shouldHaveReceived($method = null, $args = null)
    {
        if ($method === null) {
            return new HigherOrderMessage($this, "shouldHaveReceived");
        }

        $expectation = new \Mockery\VerificationExpectation($this, $method);
        if (null !== $args) {
            $expectation->withArgs($args);
        }
        $expectation->atLeast()->once();
        $director = new \Mockery\VerificationDirector($this->_mockery_getReceivedMethodCalls(), $expectation);
        $this->_mockery_expectations_count++;
        $director->verify();
        return $director;
    }

    public function shouldHaveBeenCalled()
    {
        return $this->shouldHaveReceived("__invoke");
    }

    public function shouldNotHaveReceived($method = null, $args = null)
    {
        if ($method === null) {
            return new HigherOrderMessage($this, "shouldNotHaveReceived");
        }

        $expectation = new \Mockery\VerificationExpectation($this, $method);
        if (null !== $args) {
            $expectation->withArgs($args);
        }
        $expectation->never();
        $director = new \Mockery\VerificationDirector($this->_mockery_getReceivedMethodCalls(), $expectation);
        $this->_mockery_expectations_count++;
        $director->verify();
        return null;
    }

    public function shouldNotHaveBeenCalled(array $args = null)
    {
        return $this->shouldNotHaveReceived("__invoke", $args);
    }

    protected static function _mockery_handleStaticMethodCall($method, array $args)
    {
        $associatedRealObject = \Mockery::fetchMock(__CLASS__);
        try {
            return $associatedRealObject->__call($method, $args);
        } catch (BadMethodCallException $e) {
            throw new BadMethodCallException(
                'Static method ' . $associatedRealObject->mockery_getName() . '::' . $method
                . '() does not exist on this mock object',
                null,
                $e
            );
        }
    }

    protected function _mockery_getReceivedMethodCalls()
    {
        return $this->_mockery_receivedMethodCalls ?: $this->_mockery_receivedM