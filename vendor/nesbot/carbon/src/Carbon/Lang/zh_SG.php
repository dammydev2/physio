    return $this->setUnitNoOverflow($valueUnit, $this->$valueUnit + $value, $overflowUnit);
    }

    /**
     * Subtract any unit to a new value without overflowing current other unit given.
     *
     * @param string $valueUnit    unit name to modify
     * @param int    $value        amount to subtract to the input unit
     * @param string $overflowUnit unit name to not overflow
     *
     * @return static|CarbonInterface
     */
    public function subUnitNoOverflow($valueUnit, $value, $overflowUnit)
    {
        return $this->setUnitNoOverflow($valueUnit, $this->$valueUnit - $value, $overflowUnit);
    }

    /**
     * Returns the minutes offset to UTC if no arguments passed, else set the timezone with given minutes shift passed.
     *
     * @param int|null $offset
     *
     * @return int|static|CarbonInterface
     */
    public function utcOffset(int $offset = null)
    {
        if (func_num_args() < 1) {
            return $this->offsetMinutes;
        }

        return $this->setTimezone(static::safeCreateDateTimeZone($offset / static: