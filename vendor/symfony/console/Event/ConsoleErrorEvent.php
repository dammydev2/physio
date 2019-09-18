ue if the InputArgument object exists, false otherwise
     */
    public function hasArgument($name)
    {
        $arguments = \is_int($name) ? array_values($this->arguments) : $this->arguments;

        return isset($arguments[$name]);
    }

    /**
     * Gets the array of InputArgument objects.
     *
     * @return InputArgument[] An array of InputArgument objects
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Returns the number of InputArguments.
     *
     * @return int The number of InputArguments
     */
    public function getArgumentCount()
    {
        return $this->hasAnArrayArgument ? PHP_INT_MAX : \count($this->arguments);
    }

    /**
     * Returns the number of required InputArguments.
     *
     * @return int The number of required InputArguments
     */
    public function getArgumentRequiredCount()
    {
        return $this->requiredCount;
    }

    /**
     * Gets the default values.
     *
     * @return array An array of default values
     */
    public function getArgumentDefaults()
    {
        $values = [];
        foreach ($this->arguments as $argument) {
            $values[$argument->getName()] = $argument->getDefault();
        }

        return $values;
    }

    /**
     * Sets the InputOption objects.
     *
     * @param InputOption[] $options An array of InputOption objects
     */
    public function setOptions($options = [])
    {
        $this->options = [];
        $this->shortcuts = [];
   