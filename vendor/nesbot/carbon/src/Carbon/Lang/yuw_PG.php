var CarbonTimeZone $timezone */
        $timezone = CarbonTimeZone::instance($object);
        if ($timezone && is_int($originalObject ?: $object)) {
            $timezone = $timezone->toRegionTimeZone($this);
        }

        return $timezone;
    }

    /**
     * Get the TimeZone associated with the Carbon instance (as CarbonTimeZone).
     *
     * @return CarbonTimeZone
     *
     * @link http://php.net/manual/en/datetime.gettimezone.php
     */
    public function getTimezone()
    {
        return CarbonTimeZone::instance(parent::getTimezone());
    }

    /**
     * List of minimum and maximums for each unit.
     *
     * @return array
     */
    protected static function getRangesByUnit()
    {
        return [
            // @call roundUnit
            'year' => [1, 9999],
            // @call roundUnit
            'month' => [1, static::MONTHS_PER_YEAR],
            // @call roundUnit
            'day' => [1, 31],
            // @call roundUnit
            