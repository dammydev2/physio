
     * Store a mock and set its container reference
     *
     * @param \Mockery\Mock $mock
     * @return \Mockery\MockInterface
     */
    public function rememberMock(\Mockery\MockInterface $mock)
    {
        if (!isset($this->_mocks[get_class($mock)])) {
            $this->_mocks[get_class($mock)] = $mock;
        } else {
            /**
             * This condition triggers for an instance mock where origin mock
             * is already remembered
             */
            $this->_mocks[] = $mock;
        }
        return $mock;
    }

    /**
     * Retrieve the last remembered mock object, which is the same as saying
     * retrieve the current mock being programmed where you have yet to call
     * mock() to change it - thus why the method name is "self" since it will be
     * be used during the programming of the same mock.
     *
     * @return \Mockery\Mock
     */
    public function self()
    {
        $mocks = array_values($this->_mocks);
        $index = count($mocks) - 1;
        return $mocks[$index];
    }

    /**
     * Return a specific remembered mock according to the array index it
     * was stored to in this container instance
     *
     * @return \Mockery\Mock
     */
    public function fetchMock($reference)
    {
        if (isset($this->_mocks[$reference])) {
            return $this->_mocks[$reference];
        }
    }

    protected function _getInstance($mockName, $constructorArgs = null)
    {
        if ($constructorArgs !== null) {
            $r = new \ReflectionClass($mockName);
            return $r->newInstanceArgs($constructorArgs);
        }

        try {
            $instantiator = new Ins