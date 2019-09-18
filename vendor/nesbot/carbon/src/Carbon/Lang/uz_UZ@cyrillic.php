$tz = null)
    {
        return static::create($year, $month, $day, 0, 0, 0, $tz);
    }

    /**
     * Create a Carbon instance from just a time. The date portion is set to today.
     *
     * @param int|null                  $hour
     * @param int|null                  $minute
     * @param int|null                  $second
     * @param \DateTimeZone|string|null $tz
     *
     * @throws \InvalidArgumentException
     *
     * @return static|CarbonInterface
     */
    public static function createFromTime($hour = 0, $minute = 0, $second = 0, $tz = null)
    {
        return static::create(null, null, null, $hour, $minute, $second, $tz);
    }

    /**
     * Create a Carbon instance from a time string. The date portion is set to today.
     *
     * @param string                    $time
     * @param \DateTimeZone|string|null $tz
     *
     * @throws \InvalidArgumentException
     *
     * @return static|CarbonInterface
     */
    public static function createFromTimeString($time, $tz = null)
    {
        return static::today($tz)->setTimeFromTimeString($time);
    }

    /**
     * @param string                          $format     Datetime format
     * @param stri