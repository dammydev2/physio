ce
     *
     * @return int
     */
    public function diffInHours($date = null, $absolute = true)
    {
        return (int) ($this->diffInSeconds($date, $absolute) / static::SECONDS_PER_MINUTE / static::MINUTES_PER_HOUR);
    }

    /**
     * Get the difference in hours using timestamps.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInRealHours($date = null, $absolute = true)
    {
        return (int) ($this->diffInRealSeconds($date, $absolute) / static::SECONDS_PER_MINUTE / static::MINUTES_PER_HOUR);
    }

    /**
     * Get the difference in minutes.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInMinutes($date = null, $absolute = true)
    {
        return (int) ($this->diffInSeconds($date, $absolute) / static::SECONDS_PER_MINUTE);
    }

    /**
     * Get the difference in minutes using timestamps.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInRealMinutes($date = null, $absolute = true)
    {
        return (int) ($this->diffInRealSeconds($date, $absolute) / static::SECONDS_PER_MINUTE);
    }

    /**
     * Get the difference in seconds.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInSeconds($date = null, $absolute = true)
    {
        $diff = $this->diff($this->resolveCarbon($date));
        if ($diff->days === 0) {
            $diff = static::fixDiffInterval($diff, $absolute);
        }
        $value = ((($diff->days * static::HOURS_PER_DAY) +
            $diff->h) * static::MINUTES_PER_HOUR +
            $diff->i) * static::SECONDS_PER_MINUTE +
            $diff->s;

        return $absolute || !$diff->invert ? $value : -$value;
    }

    /**
     * Get the difference in microseconds.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInMicroseconds($date = null, $absolute = true)
    {
        $diff = $this->diff($this->resolveCarbon($date));
        $value = (int) round((((($diff->days * static::HOURS_PER_DAY) +
            $diff->h) * static::MINUTES_PER_HOUR +
            $diff->i) * static::SECONDS_PER_MINUTE +
            ($diff->f + $diff->s)) * static::MICROSECONDS_PER_SECOND);

        return $absolute || !$diff->invert ? $value : -$value;
    }

    /**
     * Get the difference in milliseconds.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInMilliseconds($date = null, $absolute = true)
    {
        return (int) ($this->diffInMicroseconds($date, $absolute) / static::MICROSECONDS_PER_MILLISECOND);
    }

    /**
     * Get the difference in seconds using timestamps.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInRealSeconds($date = null, $absolute = true)
    {
        /** @var CarbonInterface $date */
        $date = $this->resolveCarbon($date);
        $value = $date->getTimestamp() - $this->getTimestamp();

        return $absolute ? abs($value) : $value;
    }

    /**
     * Get the difference in microseconds using timestamps.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInRealMicroseconds($date = null, $absolute = true)
    {
        /** @var CarbonInterface $date */
        $date = $this->resolveCarbon($date);
        $value = ($date->timestamp - $this->timestamp) * static::MICROSECONDS_PER_SECOND +
            $date->micro - $this->micro;

        return $absolute ? abs($value) : $value;
    }

    /**
     * Get the difference in milliseconds using timestamps.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInRealMilliseconds($date = null, $absolute = true)
    {
        return (int) ($this->diffInRealMicroseconds($date, $absolute) / static::MICROSECONDS_PER_MILLISECOND);
    }

    /**
     * Get the difference in seconds as float (microsecond-precision).
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return float
     */
    public function floatDiffInSeconds($date = null, $absolute = true)
    {
        return $this->diffInMicroseconds($date, $absolute) / static::MICROSECONDS_PER_SECOND;
    }

    /**
     * Get the difference in minutes as float (microsecond-precision).
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return float
     */
    public function floatDiffInMinutes($date = null, $absolute = true)
    {
        return $this->floatDiffInSeconds($date, $absolute) / static::SECONDS_PER_MINUTE;
    }

    /**
     * Get the difference in hours as float (microsecond-precision).
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return float
     */
    public function floatDiffInHours($date = null, $absolute = true)
    {
        return $this->floatDiffInMinutes($date, $absolute) / static::MINUTES_PER_HOUR;
    }

    /**
     * Get the difference in days as float (microsecond-precision).
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return float
     */
    public function floatDiffInDays($date = null, $absolute = true)
    {
        $hoursDiff = $this->floatDiffInHours($date, $absolute);

        return ($hoursDiff < 0 ? -1 : 1) * $this->diffInDays($date) + fmod($hoursDiff, static::HOURS_PER_DAY) / static::HOURS_PER_DAY;
    }

    /**
     * Get the difference in months as float (microsecond-precision).
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool