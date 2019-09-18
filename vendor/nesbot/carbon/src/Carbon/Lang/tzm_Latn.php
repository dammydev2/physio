startOfDecade()
    {
        $year = $this->year - $this->year % static::YEARS_PER_DECADE;

        return $this->setDate($year, 1, 1)->startOfDay();
    }

    /**
     * Resets the date to end of the decade and time to 23:59:59.999999
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->endOfDecade();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function endOfDecade()
    {
        $year = $this->year - $this->year % static::YEARS_PER_DECADE + static::YEARS_PER_DECADE - 1;

        return $this->setDate($year, 12, 31)->endOfDay();
    }

    /**
     * Resets the date to the first day of the century and the time to 00:00:00
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfCentury();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfCentury()
    {
        $year = $this->year - ($this->year - 1) % static::YEARS_PER_CENTURY;

        return $this->setDate($year, 1, 1)->startOfDay();
    }

    /**
     * Resets the date to end of the century and time to 23:59:59.999999
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->endOfCentury();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function endOfCentury()
    {
        $year = $this->year - 1 - ($this->year - 1) % static::YEARS_PER_CENTURY + static::YEARS_PER_CENTURY;

        return $this->setDate($year, 12, 31)->endOfDay();
    }

    /**
     * Resets the date to the first day of the century and the time to 00:00:00
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfMillennium();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfMillennium()
    {
        $year = $this->year - ($this->year - 1) % static::YEARS_PER_MILLENNIUM;

        return $this->setDate($year, 1, 1)->startOfDay();
    }

    /**
     * Resets the date to end of the century and time to 23:59:59.999999
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->endOfMillennium();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function endOfMillennium()
    {
 