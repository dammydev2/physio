HandlerInterface
     */
    public function popHandler()
    {
        if (!$this->handlers) {
            throw new \LogicException('You tried to pop from an empty handler stack.');
        }

        return array_shift($this->handlers);
    }

    /**
     * Set handlers, replacing all existing ones.
     *
     * If a map is passed, keys will be ignored.
     *
     * @param  HandlerInterface[] $handlers
     * @return $this
     */
    public function setHandlers(array $handlers)
    {
        $this->handlers = array();
        foreach (array_reverse($handlers) as $handler) {
            $this->pushHandler($handler);
        }

        return $this;
    }

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * Adds a processor on to the stack.
     *
     * @param  callable $callback
     * @return $this
     */
    public function p