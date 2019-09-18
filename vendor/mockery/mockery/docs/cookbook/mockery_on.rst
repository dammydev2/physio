turn $this;
    }

    /**
     * Set a return value, or sequential queue of return values
     *
     * @param mixed[] ...$args
     * @return self
     */
    public function andReturn(...$args)
    {
        $this->_returnQueue = $args;
        return $this;
    }

    /**
     * Set a return value, or sequential queue of return values
     *
     * @param mixed[] ...$args
     * @return self
     */
    public function andReturns(...$args)
    {
        return call_user_func_array([$this, 'andReturn'], $args);
    }

    /**
     * Return this mock, like a fluent interface
     *
     * @return self
     */
    public function andReturnSelf()
    {
        return $this->andReturn($this->_mock);
    }

    /**
     * Set a sequential queue of return values with an array
     *
     * @param array $values
     * @return self
     */
    public function andReturnValues(array $values)
    {
        call_user_func_array(array($this, 'andReturn'), $values);
        return $this;
    }

    /**
     * Set a closure or sequence of closures with which to generate return
     * values. The arguments passed to the expected method are passed to the
     * closures as parameters.
     *
     * @param callable[] ...$args
     * @return self
     */
    public function andReturnUsing(...$args)
    {
        $this->_closureQueue = $args;
        return $this;
    }

    /**
     * Return a self-returning black hole object.
     *
     * @return self
     */
    public function andReturnUndefined()
    {
        $this->andReturn(new \Mockery\Undefined);
        return $this;
    }

    /**
     * Return null. This is merely a language construct for Mock describing.
     *
     * @return self
     */
    public function andReturnNull()
    {
        return $this->andReturn(null);
    }

    public function andReturnFalse()
    {
        return $this->andReturn(false);
    }

    public function andReturnTrue()
    {
        return $this->andReturn(true);
    }

    /**
     * Set Exception class and arguments to that class to be thrown
     *
     * @param string|\Exception $exception
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     * @return self
     */
    public function andThrow($exception, $message = '', $code = 0, \Exception $previous = null)
    {
        $this->_throw = true;
        if (is_object($exception)) {
            $this->andReturn($exception);
        } else {
            $this->andReturn(new $exception($message, $code, $previous));
        }
        return $this;
    }

    public function andThrows($exception, $message = '', $code = 0, \Exception $previous = null)
    {
        return $this->andThrow($exception, $message, $code, $previous);
    }

    /**
     * Set Exception classes to be thrown
     *
     * @param array $exceptions
     * @return self
     */
    public function andThrowExceptions(array $exceptions)
    {
        $this->_throw = true;
        foreach ($exceptions as $exception) {
            if (!is_object($exception)) {
    