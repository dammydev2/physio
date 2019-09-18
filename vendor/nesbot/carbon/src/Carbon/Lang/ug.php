 and seconds become 0
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfHour();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfHour()
    {
        return $this->setTime($this->hour, 0, 0, 0);
    }

    /**
     * Modify to end of current hour, minutes and seconds become 59
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->endOfHour();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function endOfHour()
    {
        return $this->setTime($this->hour, static::MINUTES_PER_HOUR - 1, static::SECONDS_PER_MINUTE - 1, static::MICROSECONDS_PER_SECOND - 1);
    }

    /**
     * Modify to start of current minute, seconds become 0
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfMinute();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfMinute()
    {
        return $this->setTime($this->hour, $this->minute, 0, 0);
    }

    /**
     * Modify to end of current minute, seconds become 59
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->endOfMinute();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function endOfMinute()
    {
        return $this->setTime($this->hour, $this->minute, static::SECONDS_PER_MINUTE - 1, static::MICROSECONDS_PER_SECOND - 1);
    }

    /**
     * Modify to start of current second, microseconds become 0
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16.334455')
     *   ->startOfSecond()
     *   ->format('H:i:s.u');
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfSecond()
    {
        return $this->setTime($this->hour, $this->minute, $this->second, 0);
    }

    /**
     * Modify to end of current second, microseconds become 999999
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16.334455')
     *   ->endOfSecond()
     *   ->format('H:i:s.u');
     * ```
     *
     * @return static|CarbonInterface
     */
    public function endOfSecond()
    {
        return $this->setTime($this->hour, $this->minute, $this->second, static::MICROSECONDS_PER_SECOND - 1);
    }

    /**
     * Modify to start of current given unit.
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16.334455')
     *   ->startOf('month')
     *   ->endOf('week', Carbon::FRIDAY);
     * ```
     *
     * @param string            $unit
     * @param array<int, mixed> $params
     *
     * @return static|CarbonInterface
     */
    public function startOf($unit, ...$params)
    {
        $ucfUnit = ucfirst(static::singularUnit($unit));
        $method = "startOf$ucfUnit";
        if (!method_exists($this, $method)) {
            throw new InvalidArgumentException("Unknown unit '$unit'");
        }

        return $this->$method(...$params);
    }

    /**
     * Modify to end of current given unit.
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16.334455')
     *   ->startOf('month')
     *   -