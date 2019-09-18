
    {
        $this->validateOrder();
        $this->_actualCount++;
        if (true === $this->_passthru) {
            return $this->_mock->mockery_callSubjectMethod($this->_name, $args);
        }

        $return = $this->_getReturnValue($args);
        $this->throwAsNecessary($return);
        $this->_setValues();

        return $return;
    }

    /**
     * Throws an exception if the expectation has been configured to do so
     *
     * @throws \Exception|\Throwable
     * @return void
     */
    private function throwAsNecessary($return)
    {
        if (!$this->_throw) {
            return;
        }

        $type = version_compare(PHP_VERSION, '7.0.0') >= 0
            ? "\Throwable"
            : "\Exception";

        if ($retur