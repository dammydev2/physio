stance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface $date now if null
     *
     * @return static
     */
    public function setTimeFrom($date = null)
    {
        $date = $this->resolveCarbon($date);

        return $this->setTime($date->hour, $date->minute, $date->second, $date->microsecond);
    }

    /**
     * Set the date and time for this instance to that of the passed instance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface $date
     *
     * @return static
     */
    public function setDateTimeFrom($date = null)
    {
        $date = $this->resolveCarbon($date);

        return $this->modify($date->rawFormat('Y-m-d H:i:s.u'));
    }

    /**
     * Get the days of the week
     *
     * @return array
     */
    public static function getDays()
    {
        return static::$days;
    }

    ///////////////////////////////////////////////////////////////////
    /////////////////////// WEEK SPECIAL DAYS /////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Get the first day of week
     *
     * @return int
     */
    public static function getWeekStartsAt()
    {
        return static::$weekStartsAt;
    }

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             Use $weekEndsAt optional parameter instead when using endOfWeek method. You can also use the
     *             'first_day_of_week' locale setting to change the start of week according to current locale
     *             selected and implicitly the end of week.
     *
     * Set the first day of week
     *
     * @param int $day week start day
     *
     * @return void
     */
    public static function setWeekStartsAt($day)
    {
        static::$weekStartsAt = max(0, (7 + $day) % 7);
    }

    /**
     * Get the last day of week
     *
     * @return int
     */
    public static function getWeekEndsAt()
    {
        return static::$weekEndsAt;
    }

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             Use $weekStartsAt optional parameter instead when using startOfWeek, floorWeek, ceilWeek
     *             or roundWeek method. You can also use the 'first_day_of_week' locale setting to change the
     *             start of week according to current locale selected and implicitly the end of week.
     *
     * Set the last day of week
     *
     * @param int $day
     *
     * @return void
     */
    public static function setWeekEndsAt($day)
    {
        static::$weekEndsAt = max(0, (7 + $day) % 7);
    }

    /**
     * Get weekend days
     *
     * @return array
     */
    public static function getWeekendDays()
    {
        return static::$weekendDays;
    }

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You shou