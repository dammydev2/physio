 *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return bool   Whether the record has been processed
     */
    public function alert($message, array $context = array())
    {
        return $this->addRecord(static::ALERT, $message, $context);
    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string $message The log message
     * @param  array  $context The log context
     * @return bool   Whether the record has been processed
     */
    public function emerg($message, array $context = array())
    {
        return $this->addRecord(static::EMERGENCY, $message, $context);
    }

    /