function) && method_exists(static::class, $function)) {
            $function = [static::class, $function];
        }

        return $function(...func_get_args());
    }

    /**
     * Create a carbon instance from a localized string (in French, Japanese, Arabic, etc.).
     *
     * @param string                    $time
     * @param string                    $locale
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function parseFromLocale($time, $locale, $tz = null)
    {
        return static::rawParse(static::translateTimeString($time, $locale, 'en'), $tz);
    }

    /**
     * Get a Carbon instance for the current date and time.
     *
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function now($tz = null)
    {
        return new static(null, $tz);
    }

    /**
     * Create a Carbon instance for today.
     *
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function today($tz = null)
    {
        return static::rawParse('today', $tz);
    }

    /**
     * Create a Carbon instance for tomorrow.
     *
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function tomorrow($tz = null)
    {
        return static::rawParse('tomorrow', $tz);
    }

    /**
     * Create a Carbon instance for yesterday.
     *
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function yesterday($tz = null)
    {
        return static::rawParse('yesterday', $tz);
    }

    /**
     * Create a Carbon instance for the greatest supported date.
     *
     * @return static|CarbonInterface
     */
    public static function maxValue()
    {
        if (self::$PHPIntSize === 4) {
            // 32 bit
            return static::createFromTimestamp(PHP_INT_MAX); // @codeCoverageIgnore
        }

        // 64 bit
        return static::create(9999, 12, 31, 23, 59, 59);
    }

    /**
     * Create a Carbon instance for the lowest supported date.
     *
     * @return static|CarbonInterface
     */
    public static function minValue()
    {
        if (self::$PHPIntSize === 4) {
            // 32 bit
     