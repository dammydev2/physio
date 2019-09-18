
    }

    /**
     * Check if its the birthday. Compares the date/month values of the two dates.
     *
     * @param \Carbon\Carbon|\DateTimeInterface|null $date The instance to compare with or null to use current day.
     *
     * @return bool
     */
    public function isBirthday($date = null)
    {
        return $this->isSameAs('md', $date);
    }

    /**
     * Check if today is the last day of the Month
     *
     * @return bool
     */
    public function isLastOfMonth()
    {
        return $this->day === $this->daysInMonth;
    }

    /**
     * Check if the instance is start of day / midnight.
     *
     * @param bool $checkMicroseconds check time at microseconds precision
     *
     * @return bool
     */
    public function isStartOfDay($checkMicroseconds = false)
    {
        /* @var CarbonInterface $this */
        return $checkMicroseconds
            ? $this->rawFormat('H:i:s.u') === '00:00:00.000000'
            : $this->rawFormat('H:i:s') === '00:00:00';
    }

    /**
     * Check if the instance is end of day.
     *
     * @param bool $checkMicroseconds check time at microseconds precision
     *
     * @return bool
     */
    public function isEndOfDay($checkMicroseconds = false)
    {
        /* @var CarbonInterface $this */
        return $checkMicroseconds
            ? $this->rawFormat('H:i:s.u') === '23:59:59.999999'
            : $this->rawFormat('H:i:s') === '23:59:59';
    }

    /**
     * Check if the instance is start of day / midnight.
     *
     * @return bool
     */
    public function isMidnight()
    {
        return $this->isStartOfDay();
    }

    /**
     * Check if the instance is midday.
     *
     * @return bool
     */
    public function isMidday()
    {
        /* @var CarbonInterface $this */
        return $this->rawFormat('G:i:s') === static::$midDayAt.':00:00';
    }

    /**
     * Checks if the (date)time string is in a given format.
     *
     * @param string $date
     * @param string $format
     *
     * @return bool
     */
    public static fun