escribes
     * the format of a stored datetime value of this platform.
     *
     * @return string The format string.
     */
    public function getDateTimeFormatString()
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * Gets the format string, as accepted by the date() function, that describes
     * the format of a stored datetime with timezone value of this platform.
     *
     * @return string The format string.
     */
    public function getDateTimeTzFormatString()
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * Gets the format string, as accepted by the date() function, that describes
     * the format of a stored date value of this platform.
     *
     * @return string The format string.
     */
    public function getDateFormatString()
    {
        return 'Y-m-d';
    }

    /**
     * Gets the format string, a