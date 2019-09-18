 * @param string $name The InputOption shortcut
     *
     * @return bool true if the InputOption object exists, false otherwise
     */
    public function hasShortcut($name)
    {
        return isset($this->shortcuts[$name]);
    }

    /**
     * Gets an InputOption by shortcut.
     *
     * @param string $shortcut The Shortcut name
     *
     * @return InputOption An InputOption object
     */
    public function getOptionForShortcut($shortcut)
    {
        return $this->getOption($this->shortcutToName($shortcut));
    }

    /**
     * Gets an array of default values.
     *
     * @return array An array of all default values
     */
    public function getOptionDefaults()
    {
        $values = [];
        foreach ($this->options as $option) {
            $values[$option->getName()] = $option->getDefault();
        }

        return $values;
    }

    /**
     * Returns the InputOption name given a shortcut.
     *
     * @param string $shortcut The shortcut
     *
     * @return string The InputOption name
     *
     * @throws InvalidArgumentException When option given does not exist
     *
     * @internal
     */
    public function shortcutToName($shortcut)
    {
        if (!isset($this->shortcuts[$shortcut])) {
            throw new InvalidArgumentException(sprintf('The "-%s" option does not exist.', $shortcut));
        }

        return $this->shortcuts[$shortcut];
    }

    /**
     * Gets the synopsis.
     *
     * @param bool