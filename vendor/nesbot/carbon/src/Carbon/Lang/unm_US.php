ay.
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function isSameAs($format, $date = null)
    {
        /** @var DateTimeInterface $date */
        $date = $date ?: static::now($this->tz);

        static::expectDateTime($date, 'null');

        /* @var CarbonInterface $this */
        return $this->rawFormat($format) === ($date instanceof self ? $date->rawFormat($format) : $date->format($format));
    }

    /**
     * Determines if the instance is in the current unit given.
     *
     * @param string                                 $unit singular unit string
     * @param \Carbon\Carbon|\DateTimeInterface|null $date instance to compare with or null to use current day.
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function isSameUnit($unit, $date = null)
    {
        $units = [
            // @call isSameUnit
            'year' => 'Y',
            // @call isSameUnit
            'week' => 'o-W',
            // @call isSameUnit
            'day' => 'Y-m-d',
            // @call isSameUnit
            'hour' => 'Y-m-d H',
            // @call isSameUnit
            'minute' => 'Y-m-d H:i',
            // @call isSameUnit
            'second' => 'Y-m-d H:i:s',
            // @call isSameUnit
            'micro' => 'Y-m-d H:i:s.u',
            // @call isSameUnit
            'microsecond' => 'Y-m-d H:i:s.u',
        ];

        if (!isset($units[$unit])) {
            if (isset($this->$unit)) {
                $date = $date ? static::instance($date) : static::now($this->tz);

                static::expectDateTime($date);

                return $this->$unit === $date->$unit;
            }

            if ($this->localStrictModeEnabled ?? static::isStrictModeEnabled()) {
                throw new InvalidArgumentException("Bad comparison unit: '$unit'");
            }

            return false;
        }

        return $