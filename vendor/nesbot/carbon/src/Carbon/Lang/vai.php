f the format does not contain non escaped unix epoch reset flag.
            if (!preg_match("/{$nonEscaped}[!|]/", $format)) {
                $format = static::MOCK_DATETIME_FORMAT.' '.$format;
                $time = ($mock instanceof self ? $mock->rawFormat(static::MOCK_DATETIME_FORMAT) : $mock->format(static::MOCK_DATETIME_FORMAT)).' '.$time;
            }

            // Regenerate date from the modified format to base result on the mocked instance instead of now.
            $date = self::createFromFormatAndTimezone($format, $time, $tz);
        }

        if ($date instanceof DateTimeInterface) {
            $instance = static::instance($date);
            $instance::setLastErrors($lastErrors);

            return $instance;
        }

        if (static::isStrictModeEnabled()) {
            throw new InvalidArgumentException(implode(PHP_EOL, $lastErrors['errors']));
        }

        return false;
    }

    /**
     * Create a Carbon instance from a specific format.
     *
     * @param string                          $format Datetime format
     * @param string                          $time
     * @param \DateTimeZone|string|false|null $tz
     *
     * @throws InvalidArgumentException
     *
     * @return static|CarbonInterface|false
     */
    public static function createFromFormat($format, $time, $tz = null)
    {
        $function = static::$createFromFormatFunction;

        if (!$function) {
            return static::rawCreateFromFormat($format, $time, $tz);
        }

        if (is_string($function) && method_exists(