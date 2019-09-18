onthsDiff + $floorEnd->floatDiffInDays($startOfMonthAfterFloorEnd) / $floorEnd->daysInMonth + $startOfMonthAfterFloorEnd->floatDiffInDays($end) / $end->daysInMonth);
    }

    /**
     * Get the difference in year as float (microsecond-precision).
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return float
     */
    public function floatDiffInYears($date = null, $absolute = true)
    {
        $start = $this;
        $end = $this->resolveCarbon($date);
        $ascending = ($start <= $end);
        $sign = $absolute || $ascending ? 1 : -1;
        if (!$ascending) {
            $_end = $start;
            $start = $end;
            $end = $_end;
            unset($_end);
        }
        $yearsDiff = $start->diffInYears($end);
        /** @var Carbon|CarbonImmutable $floorEnd */
        $floorEnd = $start->copy()->addYears($yearsDiff);

        if ($floorEnd >= $end) {
            return $sign * $yearsDiff;
        }

        /** @var Carbon|CarbonImmutable $startOfYearAfterFloorEnd */
        $startOfYearAfterFloorEnd = $floorEnd->copy()->addYear()->startOfYear();

        if ($startOfYearAfterFloorEnd > $end) {
            return $sign * ($yearsDiff + $floorEnd->floatDiffInDays($end) / $floorEnd->daysInYear);
        }

        return $sign * ($yearsDiff + $floorEnd->floatDiffInDays($startOfYearAfterFloorEnd) / $floorEnd->daysInYear + $startOfYearAfterFloorEnd->floatDiffInDays($end) / $end->daysInYear);
    }

    /**
     * Get the difference in seconds as float (microsecond-precision) using timestamps.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return float
     */
    public function floatDiffInRealSeconds($date = null, $absolute = true)
    {
        return $this->diffInRealMicroseconds($date, $absolute) / static::MICROSECONDS_PER_SECOND;
    }

    /**
     * Get the difference in minutes as float (microsecond-precision) using timestamps.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return float
     */
    public function floatDiffInRealMinutes($date = null, $absolute = true)
    {
        return $this->floatDiffInRealSeconds($date, $absolute) / static::SECONDS_PER_MINUTE;
    }

    /**
     * Get the difference in hours as float (microsecond-precision) using timestamps.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return float
     */
    public function floatDiffInRealHours($date = null, $absolute = true)
    {
        return $this->floatDiffInRealMinutes($date, $absolute) / static::MINUTES_PER_HOUR;
    }

    /**
     * Get the difference in days as float (microsecond-precision).
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return float
     */
    public function floatDiffInRealDays($date = null, $absolute = true)
    {
        $hoursDiff = $this->floatDiffInRealHours($date, $absolute);

        return ($hoursDiff < 0 ? -1 : 1) * $this->diffInDays($date) + fmod($hoursDiff, static::HOURS_PER_DAY) / static::HOURS_PER_DAY;
    }

    /**
     * Get the difference in months as float (microsecond-precision) using timestamps.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return float
     */
    public function floatDiffInRealMonths($date = null, $absolute = true)
    {
        $start = $this;
        $end = $this->resolveCarbon($date);
        $ascending = ($start <= $end);
        $sign = $absolute || $ascending ? 1 : -1;
        if (!$ascending) {
            $_end = $start;
            $start = $end;
            $end = $_end;
            unset($_end);
        }
        $monthsDiff = $start->diffInMonths($end);
        /** @var Carbon|CarbonImmutable $floorEnd */
        $floorEnd = $start->copy()->addMonths($monthsDiff);

        if ($floorEnd >= $end) {
            return $sign * $monthsDiff;
        }

        /** @var Carbon|CarbonImmutable $startOfMonthAfterFloorEnd */
        $startOfMonthAfterFloorEnd = $floorEnd->copy()->addMonth()->startOfMonth();

        if ($startOfMonthAfterFloorEnd > $end) {
            return $sign * ($monthsDiff + $floorEnd->floatDiffInRealDays($end) / $floorEnd->daysInMonth);
        }

        return $sign * ($monthsDiff + $floorEnd->floatDiffInRealDays($startOfMonthAfterFloorEnd) / $floorEnd->daysInMonth + $startOfMonthAfterFloorEnd->floatDiffInRealDays($end) / $end->daysInMonth);
    }

    /**
     * Get the difference in year as float (microsecond-precision) using timestamps.
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return float
     */
    public function floatDiffInRealYears($date = null, $absolute = true)
    {
        $start = $this;
        $end = $this->resolveCarbon($date);
        $ascending = ($start <= $end);
        $sign = $absolute || $ascending ? 1 : -1;
        if (!$ascending) {
            $_end = $start;
            $start = $end;
            $end = $_end;
            unset($_end);
        }
        $yearsDiff = $start->diffInYears($end);
        /** @var Carbon|CarbonImmutable $floorEnd */
        $floorEnd = $start->copy()->addYears($yearsDiff);

        if ($floorEnd >= $end) {
            return $sign * $yearsDiff;
        }

        /** @var Carbon|CarbonImmutable $startOfYearAfterFloorEnd */
        $startOfYearAfterFloorEnd = $floorEnd->copy()->addYear()->startOfYear();

        if ($startOfYearAfterFloorEnd > $end) {
            return $sign * ($yearsDiff + $floorEnd->floatDiffInRealDays($end) / $floorEnd->daysInYear);
        }

        return $sign * ($yearsDiff + $floorEnd->floatDiffInRealDays($startOfYearAfterFloorEnd) / $floorEnd->daysInYear + $startOfYearAfterFloorEnd->floatDiffInRealDays($end) / $end->daysInYear);
    }

    /**
     * The number of seconds since midnight.
     *
     * @return int
     */
    public function secondsSinceMidnight()
    {
        return $this->diffInSeconds($this->copy()->startOfDay());
    }

    /**
     * The number of seconds until 23:59:59.
     *
     * @return int
     */
    public function secondsUntilEndOfDay()
    {
        return $this->diffInSeconds($this->copy()->endOfDay());
    }

    /**
     * Get the difference in a human readable format in the current locale from current instance to an other
     * instance given (or now if null given).
     *
     * @example
     * ```
     * echo Carbon::tomorrow()->diffForHumans() . "\n";
     * echo Carbon::tomorrow()->diffForHumans(['parts' => 2]) . "\n";
     * echo Carbon::tomorrow()->diffForHumans(['parts' => 3, 'join' => true]) . "\n";
     * echo Carbon::tomorrow()->diffForHumans(Carbon::yesterday()) . "\n";
     * echo Carbon::tomorrow()->diffForHumans(Carbon::yesterday(), ['short' => true]) . "\n";
     * ```
     *
     * @param Carbon|\DateTimeInterface|string|array|null $other   if array passed, will be used as parameters array, see $syntax below;
     *                                                             if null passed, now will be used as comparison reference;
     *                                                             if any other type, it will be converted to date and used as reference.
     * @param int|array                                   $syntax  if array passed, parameters will be extracted from it, the array may contains:
     *                                                             - 'syntax' entry (see below)
     *                                                             - 'short' entry (see below)
     *                                                             - 'parts' entry (see below)
     *                                                             - 'options' entry (see below)
     *                                                             - 'join' entry determines how to join multiple parts of the string
     *                                                             `  - if $join is a string, it's used as a joiner glue
     *                                                             `  - if $join is a callable/closure, it get the list of string and should return a string
     *                                                             `  - if $join is an array, the first item will be the default glue, and the second item
     *                                                             `    will be used instead of the glue for the last item
     *                                                             `  - if $join is true, it will be guessed from the locale ('list' translation file entry)
     *                                                             `  - if $join is missing, a space will be used as glue
     *                                                             - 'other' entry (see above)
     *                                                             if int passed, it add modifiers:
     *                                                             Possible values:
     *                                                             - CarbonInterface::DIFF_ABSOLUTE          no modifiers
     *                                                             - CarbonInterface::DIFF_RELATIVE_TO_NOW   add ago/from now modifier
     *                                                             - CarbonInterface::DIFF_RELATIVE_TO_OTHER add before/after modifier
     *                                                             Default value: CarbonInterface::DIFF_ABSOLUTE
     * @param bool                                        $short   displays short format of time units
     * @param int                                         $parts   maximum number of parts to display (default value: 1: single unit)
     * @param int                                         $options human diff options
     *
     * @return string
     */
    public function diffForHumans($other = null, $syntax = null, $short = false, $parts = 1, $options = null)
    {
        /* @var CarbonInterface $this */
        if (is_array($other)) {
            $other['syntax'] = array_key_exists('syntax', $other) ? $other['syntax'] : $syntax;
            $syntax = $other;
            $other = $syntax['other'] ?? null;
        }

        $intSyntax = &$syntax;
        if (is_array($syntax)) {
            $syntax['syntax'] = $syntax['syntax'] ?? null;
            $intSyntax = &$syntax['syntax'];
        }
        $intSyntax = (int) ($intSyntax === null ? static::DIFF_RELATIVE_AUTO : $intSyntax);
        $intSyntax = $intSyntax === static::DIFF_RELATIVE_AUTO && $other === null ? static::DIFF_RELATIVE_TO_NOW : $intSyntax;

        $parts = min(7, max(1, (int) $parts));

        return $this->diffAsCarbonInterval($other, false)
            ->setLocalTranslator($this->getLocalTranslator())
            ->forHumans($syntax, (bool) $short, $parts, $options ?? $this->localHumanDiffOptions ?? static::getHumanDiffOptions());
    }

    /**
     * @alias diffForHumans
     *
     * Get the difference in a human readable format in the current locale from current instance to an other
     * instance given (or now if null given).
     *
     * @param Carbon|\DateTimeInterface|string|array|null $other   if array passed, will be used as pa