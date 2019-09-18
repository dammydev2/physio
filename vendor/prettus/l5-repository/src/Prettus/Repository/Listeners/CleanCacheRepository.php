rgumentDescription if the update check interval is unknown
     *
     * @param string $interval
     */
    public function setUpdateCheck($interval)
    {
        $validIntervals = [
            Checker::ALWAYS,
            Checker::DAILY,
            Checker::WEEKLY,
            Checker::MONTHLY,
            Checker::NEVER,
        ];

        if (!\in_array($interval, $validIntervals)) {
            throw new \InvalidArgumentException('invalid update check interval: ' . $interval);
        }

        $this->updateCheck = $interval;
    }

    /**
     * Get a cache file path for the update checker.
     *
     * @return string|false Return false if config file/directory is not writable
     */
    public function getUpdateCheckCacheFile()
    {
        $dir = $this->configDir ?: ConfigPaths::getCurrentConfigDir();

        return ConfigPaths::touchFileWithMkdir($dir . '/update_check.json');
    }

    /**
     * Set the startup message.
     *
     * @param string $message
     */
    public function setStartupMessage($message)
    {
        $this->startupMessage = $message;
    }

    /**
     * Get the startup message.
     *
     * @return string|null
     */
    public function getStartupMessage()
    {
        return $this->startupMessage;
    }

    /**
     * Set the prompt.
     *
     * @param string $prompt
     */
    public function setPrompt($prompt)
    {
        $this->prompt = $prompt;
    }

    /**
     * Get the prompt.
     *
     * @return string
     */
    public function getPrompt()
    {
        return $this->prompt;
    }

    /**
     * Get the force array indexes.
     *
     * @return bool
     */
    public function forceArrayIndexes()
    {
        return $this->forceArrayIndexes;
    }

    /**
     * Set the force array indexes.
     *
     * @param bool $for