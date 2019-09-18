
     *
     * @return string
     */
    public function toRfc7231String()
    {
        return $this->copy()
            ->setTimezone('GMT')
            ->rawFormat(defined('static::RFC7231_FORMAT') ? static::RFC7231_FORMAT : CarbonInterface::RFC7231_FORMAT);
    }

    /**
     * Get default array representation.
     *
     * @example
     * ```
     * var_dump(Carbon::now()->toArray());
     * ```
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'year' => $this->year,
            'month' => $this->month,
            'day' => $this->day,
            'dayOfWeek' => $this->dayOfWeek,
            'dayOfYear' => $this->dayOfYear,
            'hour' => $this->hour,
            'minute' => $this->minute,
            'second' => $this->second,
            'micro' => $this->micro,
            'timestamp' => $this->timestamp,
            'formatted' => $this->rawFormat(defined('static::DEFAULT_TO_STRING_FORMAT') ? static::DEFAULT_TO_STRING_FORMAT : CarbonInterface::DEFAULT_TO_STRING_FORMAT),
            'timezone' => $this->timezone,
        ];
    }

    /**
     * Get default object representation.
     *
     * @example
     * ```
     * var_dump(Carbon::now()->toObject());
     * ```
     *
     * @return object
     */
    public function toObject()
    {
        return (object) $this->toArray();
    }

    /**
     * Returns english human readable complete date string.
     *
     * @example
     * ```
     * echo Carbon::now()->toString();
     * ```
     *
     * @return string
     */
    public function toString()
    {
        return $this->copy()->locale('en')->isoFormat('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
    }

    /**
     * Return the ISO-8601 string (ex: 1977-04-22T06:00:00Z, if $keepOffset truthy, offset will be kept:
     * 1977-04-22T01:00:00-05:00).
     *
     * @example
     * ```
     * echo Carbon::now('America/Toronto')->toISOString() . "\n";
     * echo Carbon::now('America/Toronto')->toISOString(true) . "\n";
     * ```
     *
     * @param bool $keepOffset Pass true to keep the date offset. Else forced to UTC.
     *
     * @return null|string
     */
    public function toISOString($keepOffset = false)
    {
        if (!$this->isValid()) {
            return null;
        }

        $keepOffset = (bool) $keepOffset;
        $yearFormat = true ? 'YYYY' : 'YYYYYY';
        $tzFormat = $keepOffset ? 'Z' : '[Z]';
        $date = $keepOffset ? $this : $this->copy()->utc();

        return $date->isoFormat("$yearFormat-MM-DD[T]HH:mm:ss.SSSSSS$tzFormat");
    }

    /**
     * Return the ISO-8601 string (ex: 1977-04-22T06:00:00Z) with UTC timezone.
     *
     * @example
     * ```
     * echo Carbon::now('America/Toronto')->toJSON();
     * ```
     *
     * @return null|string
     */
    public function toJSON()
    {
        return $this->toISOString();
    }

    /**
     * Return native DateTime PHP object matching the current instance.
     *
  