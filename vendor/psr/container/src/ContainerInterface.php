*
     * @param string $input
     *
     * @return null|BaseCommand
     */
    protected function getCommand($input)
    {
        $input = new StringInput($input);
        if ($name = $input->getFirstArgument()) {
            return $this->get($name);
        }
    }

    /**
     * Check whether a command is set for the current input string.
     *
     * @param string $input
     *
     * @return bool True if the shell has a command for the given input
     */
    protected function hasCommand($input)
    {
        if (\preg_match('/([^\s]+?)(?:\s|$)/A', \ltrim($input), $match)) {
            return $this->has($match[1]);
        }

        return false;
    }

    /**
     * Get the current input prompt.
     *
     * @return string
     */
    protected function getPrompt()
    {
        if ($this->hasCode()) {
            return static::BUFF_PROMPT;
        }

        return $this->config->getPrompt() ?: static::PROMPT;
    }

    /**
     * Read a line of user input.
     *
     * This will return a line from the input buffer (if any exist). Otherwise,
     * it will ask t