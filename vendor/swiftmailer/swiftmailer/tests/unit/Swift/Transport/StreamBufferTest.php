erface $output)
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     *
     * @see execute()
     */
    public function setCode(callable $code)
    {
        if ($code instanceof \Closure) {
            $r = new \ReflectionFunction($code);
            if (null === $r->getClosureThis()) {
                $code = \Closure::bind($code, $this);
            }
        }

        $this->code = $code;

        return $this;
    }

    /**
     * Merges the application definition with the command definition.
     *
     * This method is not part of public API and should not be used directly.
     *
     * @param bool $mergeArgs Whether to merge or not the Application definition arguments to Command definition arguments
     */
    public function mergeApplicationDefinition($mergeArgs = true)
    {
        if (null === $this->application || (true === $this->applicationDefinitionMerged && ($this->applicationDefinitionMergedWithArgs || !$mergeArgs))) {
            return;
        }

        $this->definition->addOptions($this->application->getDefinition()->getOptions());

        $this->applicationDefinitionMerged = true;

        if ($mergeArgs) {
            $currentArguments = $this->definition->getArguments();
            $this->definition->setArguments($this->application->getDefinition()->getArgu