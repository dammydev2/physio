 current language using ISO replacement patterns.
     *
     * @param string      $format
     * @param string|null $originalFormat provide context if a chunk has been passed alone
     *
     * @return string
     */
    public function isoFormat(string $format, string $originalFormat = null): string;

    /**
     * Get/set the week number using given first day of week and first
     * day of year included in the first week. Or use ISO format if no settings
     * given.
     *
     * @param int|null $week
     * @param int|null $dayOfWeek
     * @param int|null $dayOfYear
     *
     * @return int|static
     */
    public function isoWeek($week = null, $dayOfWeek = null, $dayOfYear = null);

    /**
     * Set/get the week number of year using given first day of week and first
     * day of year included in the first week. Or use ISO format if no settings
     * given.
     *
     * @param int|null $year      if null, act as a getter, if not null, set the year and return current instance.
     * @param int|null $dayOfWeek first date of week from 0 (Sunday) to 6 (Saturday)
     * @param int|null $dayOfYear first day of year included in the week #1
     *
     * @return int|static
     */
    public function isoWeekYear($year = null, $dayOfWeek = null, $dayOfYear = null);

    /**
     * Get/set the ISO weekday from 1 (Monday) to 7 (Sunday).
     *
     * @param int|null $value new value for weekday if using as setter.
     *
     * @return static|CarbonInterface|int
     */
    public function isoWeekday($value = null);

    /**
     * Get the number of weeks of the current week-year using given first day of week and first
     * day of year included in the first week. Or use ISO format if no settings
     * given.
     *
     * @param int|null $dayOfWeek first date of week from 0 (Sunday) to 6 (Saturday)
     * @param int|null $dayOfYear first day of year included in the week #1
     *
     * @return int
     */
    public function isoWeeksInYear($dayOfWeek = null, $dayOfYear = null);

    /**
     * Prepare the object for JSON serialization.
     *
     * @return array|string
     */
    public function jsonSerialize();

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * last day of the current month.  Use the supplied constants
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek
     *
     * @return static|CarbonInterface
     */
    public function lastOfMonth($dayOfWeek = null);

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * last day of the current quarter.  Use the supplied constants
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek day of the week default null
     *
     * @return static|CarbonInterface
     */
    public function lastOfQuarter($dayOfWeek = null);

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * last day of the current year.  Use the supplied constants
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek day of the week default null
     *
     * @return static|CarbonInterface
     */
    public function lastOfYear($dayOfWeek = null);

    /**
     * Determines if the instance is less (before) than another
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @return bool
     */
    public function lessThan($date): bool;

    /**
     * Determines if the instance is less (before) or equal to another
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @return bool
     */
    public function lessThanOrEqualTo($date): bool;

    /**
     * Get/set the locale for the current instance.
     *
     * @param string|null $locale
     * @param string[]    ...$fallbackLocales
     *
     * @return $this|string
     */
    public function locale(string $locale = null, ...$fallbackLocales);

    /**
     * Returns true if the given locale is internally supported and has words for 1-day diff (just now, yesterday, tomorrow).
     * Support is considered enabled if the 3 words are translated in the given locale.
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public static function localeHasDiffOneDayWords($locale);

    /**
     * Returns true if the given locale is internally supported and has diff syntax support (ago, from now, before, after).
     * Support is considered enabled if the 4 sentences are translated in the given locale.
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public static function localeHasDiffSyntax($locale);

    /**
     * Returns true if the given locale is internally supported and has words for 2-days diff (before yesterday, after tomorrow).
     * Support is considered enabled if the 2 words are translated in the given locale.
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public static function localeHasDiffTwoDayWords($locale);

    /**
     * Returns true if the given locale is internally supported and has period syntax support (X times, every X, from X, to X).
     * Support is considered enabled if the 4 sentences are translated in the given locale.
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public static function localeHasPeriodSyntax($locale);

    /**
     * Returns true if the given locale is internally supported and has short-units support.
     * Support is considered enabled if either year, day or hour has a short variant translated.
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public static function localeHasShortUnits($locale);

    /**
     * Determines if the instance is less (before) than another
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @see lessThan()
     *
     * @return bool
     */
    public function lt($date): bool;

    /**
     * Determines if the instance is less (before) or equal to another
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @see lessThanOrEqualTo()
     *
     * @return bool
     */
    public function lte($date): bool;

    /**
     * Register a custom macro.
     *
     * @example
     * ```
     * $userSettings = [
     *   'locale' => 'pt',
     *   'timezone' => 'America/Sao_Paulo',
     * ];
     * Carbon::macro('userFormat', function () use ($userSettings) {
     *   return $this->copy()->locale($userSettings['locale'])->tz($userSettings['timezone'])->calendar();
     * });
     * echo Carbon::yesterday()->hours(11)->userFormat();
     * ```
     *
     * @param string          $name
     * @param object|callable $macro
     *
     * @return void
     */
    public static function macro($name, $macro);

    /**
     * Make a Carbon instance from given variable if possible.
     *
     * Always return a new instance. Parse only strings and only these likely to be dates (skip intervals
     * and recurrences). Throw an exception for invalid format, but otherwise return null.
     *
     * @param mixed $var
     *
     * @return static|CarbonInterface|null
     */
    public static function make($var);

    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @return static|CarbonInterface
     */
    public function max($date = null);

    /**
     * Create a Carbon instance for the greatest supported date.
     *
     * @return static|CarbonInterface
     */
    public static function maxValue();

    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @see max()
     *
     * @return static|CarbonInterface
     */
    public function maximum($date = null);

    /**
     * Return the meridiem of the current time in the current locale.
     *
     * @param bool $isLower if true, returns lowercase variant if available in the current locale.
     *
     * @return string
     */
    public function meridiem(bool $isLower = false): string;

    /**
     * Modify to midday, default to self::$midDayAt
     *
     * @return static|CarbonInterface
     */
    public function midDay();

    /**
     * Get the minimum instance between a given instance (default now) and the current instance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @return static|CarbonInterface
     */
    public function min($date = null);

    /**
     * Create a Carbon instance for the lowest supported date.
     *
     * @return static|CarbonInterface
     */
    public static function minValue();

    /**
     * Get the minimum instance between a given instance (default now) and the current instance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @see min()
     *
     * @return static|CarbonInterface
     */
    public function minimum($date = null);

    /**
     * Mix another object into the class.
     *
     * @example
     * ```
     * Carbon::mixin(new class {
     *   public function addMoon() {
     *     return function () {
     *       return $this->addDays(30);
     *     };
     *   }
     *   public function subMoon() {
     *     return function () {
     *       return $this->subDays(30);
     *     };
     *   }
     * });
     * $fullMoon = Carbon::create('2018-12-22');
     * $nextFullMoon = $fullMoon->addMoon();
     * $blackMoon = Carbon::create('2019-01-06');
     * $previousBlackMoon = $blackMoon->subMoon();
     * echo "$nextFullMoon\n";
     * echo "$previousBlackMoon\n";
     * ```
     *
     * @param object $mixin
     *
     * @throws \ReflectionException
     *
     * @return void
     */
    public static function mixin($mixin);

    /**
     * call \DateTime::modify if mutable or \DateTimeImmutable::modify else.
     *
     * @see https://php.net/manual/en/datetime.modify.php
     */
    public function modify($modify);

    /**
     * Determines if the instance is not equal to another
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @see notEqualTo()
     *
     * @return bool
     */
    public function ne($date): bool;

    /**
     * Modify to the next occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the next occurrence
     * of the current day of the week.  Use the supplied constants
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek
     *
     * @return static|CarbonInterface
     */
    public function next($dayOfWeek = null);

    /**
     * Go forward to the next weekday.
     *
     * @return static|CarbonInterface
     */
    public function nextWeekday();

    /**
     * Go forward to the next weekend day.
     *
     * @return static|CarbonInterface
     */
    public function nextWeekendDay();

    /**
     * Determines if the instance is not equal to another
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @return bool
     */
    public function notEqualTo($date): bool;

    /**
     * Get a Carbon instance for the current date and time.
     *
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function now($tz = null);

    /**
     * Returns a present instance in the same timezone.
     *
     * @return static|CarbonInterface
     */
    public function nowWithSameTz();

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current month. If the calculated occurrence is outside the scope
     * of the current month, then return false and no modifications are made.
     * Use the supplied constants to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $nth
     * @param int $dayOfWeek
     *
     * @return mixed
     */
    public function nthOfMonth($nth, $dayOfWeek);

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current quarter. If the calculated occurrence is outside the scope
     * of the current quarter, then return false and no modifications are made.
     * Use the supplied constants to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $nth
     * @param int $dayOfWeek
     *
     * @return mixed
     */
    public function nthOfQuarter($nth, $dayOfWeek);

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current year. If the calculated occurrence is outside the scope
     * of the current year, then return false and no modifications are made.
     * Use the supplied constants to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $nth
     * @param int $dayOfWeek
     *
     * @return mixed
     */
    public function nthOfYear($nth, $dayOfWeek);

    /**
     * Return a property with its ordinal.
     *
     * @param string      $key
     * @param string|null $period
     *
     * @return string
     */
    public function ordinal(string $key, string $period = null): string;

    /**
     * Create a carbon instance from a string.
     *
     * This is an alias for the constructor that allows better fluent syntax
     * as it allows you to do Carbon::parse('Monday next week')->fn() rather
     * than (new Carbon('Monday next week'))->fn().
     *
     * @param string|null               $time
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function parse($time = null, $tz = null);

    /**
     * Create a carbon instance from a localized string (in French, Japanese, Arabic, etc.).
     *
     * @param string                    $time
     * @param string                    $locale
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function parseFromLocale($time, $locale, $tz = null);

    /**
     * Returns standardized plural of a given singular/plural unit name (in English).
     *
     * @param string $unit
     *
     * @return string
     */
    public static function pluralUnit(string $unit): string;

    /**
     * Modify to the previous occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the previous occurrence
     * of the current day of the week.  Use the supplied constants
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek
     *
     * @return static|CarbonInterface
     */
    public function previous($dayOfWeek = null);

    /**
     * Go backward to the previous weekday.
     *
     * @return static|CarbonInterface
     */
    public function previousWeekday();

    /**
     * Go backward to the previous weekend day.
     *
     * @return static|CarbonInterface
     */
    public function previousWeekendDay();

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
    public static function rawCreateFromFormat($format, $time, $tz = null);

    /**
     * @see https://php.net/manual/en/datetime.format.php
     *
     * @param string $format
     *
     * @return string
     */
    public function rawFormat($format);

    /**
     * Create a carbon instance from a string.
     *
     * This is an alias for the constructor that allows better fluent syntax
     * as it allows you to do Carbon::parse('Monday next week')->fn() rather
     * than (new Carbon('Monday next week'))->fn().
     *
     * @param string|null               $time
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function rawParse($time = null, $tz = null);

    /**
     * Remove all macros and generic macros.
     */
    public static function resetMacros();

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather use the ->settings() method.
     *             Or you can use method variants: addMonthsWithOverflow/addMonthsNoOverflow, same variants
     *             are available for quarters, years, decade, centuries, millennia (singular and plural forms).
     * @see settings
     *
     * Reset the month overflow behavior.
     *
     * @return void
     */
    public static function resetMonthsOverflow();

    /**
     * Reset the format used to the default when type juggling a Carbon instance to a string
     *
     * @return void
     */
    public static function resetToStringFormat();

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather use the ->settings() method.
     *             Or you can use method variants: addYearsWithOverflow/addYearsNoOverflow, same variants
     *             are available for quarters, years, decade, centuries, millennia (singular and plural forms).
     * @see settings
     *
     * Reset the month overflow behavior.
     *
     * @return void
     */
    public static function resetYearsOverflow();

    /**
     * Round the current instance second with given precision if specified.
     *
     * @param float|int $precision
     * @param string    $function
     *
     * @return CarbonInterface
     */
    public function round($precision = 1, $function = 'round');

    /**
     * Round the current instance at the given unit with given precision if specified and the given function.
     *
     * @param string    $unit
     * @param float|int $precision
     * @param string    $function
     *
     * @return CarbonInterface
     */
    public function roundUnit($unit, $precision = 1, $function = 'round');

    /**
     * Round the current instance week.
     *
     * @param int $weekStartsAt optional start allow you to specify the day of week to use to start the week
     *
     * @return CarbonInterface
     */
    public function roundWeek($weekStartsAt = null);

    /**
     * The number of seconds since midnight.
     *
     * @return int
     */
    public function secondsSinceMidnight();

    /**
     * The number of seconds until 23:59:59.
     *
     * @return int
     */
    public function secondsUntilEndOfDay();

    /**
     * Return a serialized string of the instance.
     *
     * @return string
     */
    public function serialize();

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather transform Carbon object before the serialization.
     *
     * JSON serialize all Carbon instances using the given callback.
     *
     * @param callable $callback
     *
     * @return void
     */
    public static function serializeUsing($callback);

    /**
     * Set a part of the Carbon object
     *
     * @param string|array             $name
     * @param string|int|\DateTimeZone $value
     *
     * @throws InvalidArgumentException|ReflectionException
     *
     * @return $this
     */
    public function set($name, $value = null);

    public function setDate($year, $month, $day);

    /**
     * Set the year, month, and date for this instance to that of the passed instance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface $date now if null
     *
     * @return static
     */
    public function setDateFrom($date = null);

    /**
     * Set the date and time all together.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @param int $microseconds
     *
     * @return static|CarbonInterface
     */
    public function setDateTime($year, $month, $day, $hour, $minute, $second = 0, $microseconds = 0);

    /**
     * Set the date and time for this instance to that of the passed instance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface $date
     *
     * @return static
     */
    public function setDateTimeFrom($date = null);

    /**
     * Set the fallback locale.
     *
     * @see https://symfony.com/doc/current/components/translation.html#fallback-locales
     *
     * @param string $locale
     */
    public static function setFallbackLocale($locale);

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather use the ->settings() method.
     * @see settings
     *
     * @param int $humanDiffOptions
     */
    public static function setHumanDiffOptions($humanDiffOptions);

    /**
     * call \DateTime::setISODate if mutable or \DateTimeImmutable::setISODate else.
     *
     * @see https://php.net/manual/en/datetime.setisodate.php
     */
    public function setISODate($year, $week, $day = 1);

    /**
     * Set the translator for the current instance.
     *
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     *
     * @return $this
     */
    public function setLocalTranslator(\Symfony\Component\Translation\TranslatorInterface $translator);

    /**
     * Set the current translator locale and indicate if the source locale file exists.
     * Pass 'auto' as locale to use closest language from the current LC_TIME locale.
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public static function setLocale($locale);

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather consider mid-day is always 12pm, then if you need to test if it's an other
     *             hour, test it explicitly:
     *                 $date->format('G') == 13
     *             or to set explicitly to a given hour:
     *                 $date->setTime(13, 0, 0, 0)
     *
     * Set midday/noon hour
     *
     * @param int $hour midday hour
     *
     * @return void
     */
    public static function setMidDayAt($hour);

    /**
     * Set a Carbon instance (real or mock) to be returned when a "now"
     * instance is created.  The provided instance will be returned
     * specifically under the following conditions:
     *   - A call to the static now() method, ex. Carbon::now()
     *   - When a null (or blank string) is passed to the constructor or parse(), ex. new Carbon(null)
     *   - When the string "now" is passed to the constructor or parse(), ex. new Carbon('now')
     *   - When a string containing the desired time is passed to Carbon::parse().
     *
     * Note the timezone parameter was left out of the examples above and
     * has no affect as the mock value will be returned regardless of its value.
     *
     * To clear the test instance call this method using the default
     * parameter of null.
     *
     * /!\ Use this method for unit tests only.
     *
     * @param CarbonInterface|string|null $testNow real or mock Carbon instance
     */
    public static function setTestNow($testNow = null);

    /**
     * call \DateTime::setTime if mutable or \DateTimeImmutable::setTime else.
     *
     * @see https://php.net/manual/en/datetime.settime.php
     */
    public function setTime($hour, $minute, $second = 0, $microseconds = 0);

    /**
     * Set the hour, minute, second and microseconds for this instance to that of the passed instance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface $date now if null
     *
     * @return static
     */
    public function setTimeFrom($date = null);

    /**
     * Set the time by time string.
     *
     * @param string $time
     *
     * @return static|CarbonInterface
     */
    public function setTimeFromTimeString($time);

    /**
     * call \DateTime::setTimestamp if mutable or \DateTimeImmutable::setTimestamp else.
     *
     * @see https://php.net/manual/en/datetime.settimestamp.php
     */
    public function setTimestamp($unixtimestamp);

    /**
     * Set the instance's timezone from a string or object.
     *
     * @param \DateTimeZone|string $value
     *
     * @return static|CarbonInterface
     */
    public function setTimezone($value);

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather let Carbon object being casted to string with DEFAULT_TO_STRING_FORMAT, and
     *             use other method or custom format passed to format() method if you need to dump an other string
     *             format.
     *
     * Set the default format used when type juggling a Carbon instance to a string
     *
     * @param string|Closure|null $format
     *
     * @return void
     */
    public static function setToStringFormat($format);

    /**
     * Set the default translator instance to use.
     *
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     *
     * @return void
     */
    public static function setTranslator(\Symfony\Component\Translation\TranslatorInterface $translator);

    /**
     * Set specified unit to new given value.
     *
     * @param string $unit  year, month, day, hour, minute, second or microsecond
     * @param int    $value new value for given unit
     *
     * @return static
     */
    public function setUnit($unit, $value = null);

    /**
     * Set any unit to a new value without overflowing current other unit given.
     *
     * @param string $valueUnit    unit name to modify
     * @param int    $value        new value for the input unit
     * @param string $overflowUnit unit name to not overflow
     *
     * @return static|CarbonInterface
     */
    public function setUnitNoOverflow($valueUnit, $value, $overflowUnit);

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather use UTF-8 language packages on every machine.
     *
     * Set if UTF8 will be used for localized date/time.
     *
     * @param bool $utf8
     */
    public static function setUtf8($utf8);

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             Use $weekStartsAt optional parameter instead when using startOfWeek, floorWeek, ceilWeek
     *             or roundWeek method. You can also use the 'first_day_of_week' locale setting to change the
     *             start of week according to current locale selected and implicitly the end of week.
     *
     * Set the last day of week
     *
     * @param int $day
     *
     * @return void
     */
    public static function setWeekEndsAt($day);

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             Use $weekEndsAt optional parameter instead when using endOfWeek method. You can also use the
     *             'first_day_of_week' locale setting to change the start of week according to current locale
     *             selected and implicitly the end of week.
     *
     * Set the first day of week
     *
     * @param int $day week start day
     *
     * @return void
     */
    public static function setWeekStartsAt($day);

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather consider week-end is always saturday and sunday, and if you have some custom
     *             week-end days to handle, give to those days an other name and create a macro for them:
     *
     *             ```
     *             Carbon::macro('isDayOff', function ($date) {
     *                 return $date->isSunday() || $date->isMonday();
     *             });
     *             Carbon::macro('isNotDayOff', function ($date) {
     *                 return !$date->isDayOff();
     *             });
     *             if ($someDate->isDayOff()) ...
     *             if ($someDate->isNotDayOff()) ...
     *             // Add 5 not-off days
     *             $count = 5;
     *             while ($someDate->isDayOff() || ($count-- > 0)) {
     *                 $someDate->addDay();
     *             }
     *             ```
     *
     * Set weekend days
     *
     * @param array $days
     *
     * @return void
     */
    public static function setWeekendDays($days);

    /**
     * Set specific options.
     *  - strictMode: true|false|null
     *  - monthOverflow: true|false|null
     *  - yearOverflow: true|false|null
     *  - humanDiffOptions: int|null
     *  - toStringFormat: string|Closure|null
     *  - toJsonFormat: string|Closure|null
     *  - locale: string|null
     *  - timezone: \DateTimeZone|string|int|null
     *  - macros: array|null
     *  - genericMacros: array|null
     *
     * @param array $settings
     *
     * @return $this
     */
    public function settings(array $settings);

    /**
     * Set the instance's timezone from a string or object and add/subtract the offset difference.
     *
     * @param \DateTimeZone|string $value
     *
     * @return static|CarbonInterface
     */
    public function shiftTimezone($value);

    /**
     * Get the month overflow global behavior (can be overridden in specific instances).
     *
     * @return bool
     */
    public static function shouldOverflowMonths();

    /**
     * Get the month overflow global behavior (can be overridden in specific instances).
     *
     * @return bool
     */
    public static function shouldOverflowYears();

    /**
     * @alias diffForHumans
     *
     * Get the difference in a human readable format in the current locale from current instance to an other
     * instance given (or now if null given).
     */
    public function since($other = null, $syntax = null, $short = false, $parts = 1, $options = null);

    /**
     * Returns standardized singular of a given singular/plural unit name (in English).
     *
     * @param string $unit
     *
     * @return string
     */
    public static function singularUnit(string $unit): string;

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
    public function startOf($unit, ...$params);

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
    public function startOfCentury();

    /**
     * Resets the time to 00:00:00 start of day
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfDay();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfDay();

    /**
     * Resets the date to the first day of the decade and the time to 00:00:00
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfDecade();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfDecade();

    /**
     * Modify to start of current hour, minutes and seconds become 0
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfHour();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfHour();

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
    public function startOfMillennium();

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
    public function startOfMinute();

    /**
     * Resets the date to the first day of the month and the time to 00:00:00
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfMonth();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfMonth();

    /**
     * Resets the date to the first day of the quarter and the time to 00:00:00
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfQuarter();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfQuarter();

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
    public function startOfSecond();

    /**
     * Resets the date to the first day of week (defined in $weekStartsAt) and the time to 00:00:00
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfWeek() . "\n";
     * echo Carbon::parse('2018-07-25 12:45:16')->locale('ar')->startOfWeek() . "\n";
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfWeek(Carbon::SUNDAY) . "\n";
     * ```
     *
     * @param int $weekStartsAt optional start allow you to specify the day of week to use to start the week
     *
     * @return static|CarbonInterface
     */
    public function startOfWeek($weekStartsAt = null);

    /**
     * Resets the date to the first day of the year and the time to 00:00:00
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfYear();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfYear();

    /**
     * Subtract given units or interval to the current instance.
     *
     * @example $date->sub('hour', 3)
     * @example $date->sub(15, 'days')
     * @example $date->sub(CarbonInterval::days(4))
     *
     * @param string|DateInterval $unit
     * @param int                 $value
     * @param bool|null           $overflow
     *
     * @return CarbonInterface
     */
    public function sub($unit, $value = 1, $overflow = null);

    public function subRealUnit($unit, $value = 1);

    /**
     * Subtract given units to the current instance.
     *
     * @param string    $unit
     * @param int       $value
     * @param bool|null $overflow
     *
     * @return CarbonInterface
     */
    public function subUnit($unit, $value = 1, $overflow = null);

    /**
     * Subtract any unit to a new value without overflowing current other unit given.
     *
     * @param string $valueUnit    unit name to modify
     * @param int    $value        amount to subtract to the input unit
     * @param string $overflowUnit unit name to not overflow
     *
     * @return static|CarbonInterface
     */
    public function subUnitNoOverflow($valueUnit, $value, $overflowUnit);

    /**
     * Subtract given units or interval to the current instance.
     *
     * @see sub()
     *
     * @param string|DateInterval $unit
     * @param int                 $value
     * @param bool|null           $overflow
     *
     * @return CarbonInterface
     */
    public function subtract($unit, $value = 1, $overflow = null);

    /**
     * Get the difference in a human readable format in the current locale from current instance to an other
     * instance given (or now if null given).
     *
     * @return string
     */
    public function timespan($other = null, $timezone = null);

    /**
     * Set the instance's timestamp.
     *
     * @param int $value
     *
     * @return static|CarbonInterface
     */
    public function timestamp($value);

    /**
     * @alias setTimezone
     *
     * @param \DateTimeZone|string $value
     *
     * @return static|CarbonInterface
     */
    public function timezone($value);

    /**
     * Get the difference in a human readable format in the current locale from an other
     * instance given (or now if null given) to current instance.
     *
     * When comparing a value in the past to default now:
     * 1 hour from now
     * 5 months from now
     *
     * When comparing a value in the future to default now:
     * 1 hour ago
     * 5 months ago
     *
     * When comparing a value in the past to another value:
     * 1 hour after
     * 5 months after
     *
     * When comparing a value in the future to another value:
     * 1 hour before
     * 5 months before
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
    public function to($other = null, $syntax = null, $short = false, $parts = 1, $options = null);

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
    public function toArray();

    /**
     * Format the instance as ATOM
     *
     * @example
     * ```
     * echo Carbon::now()->toAtomString();
     * ```
     *
     * @return string
     */
    public function toAtomString();

    /**
     * Format the instance as COOKIE
     *
     * @example
     * ```
     * echo Carbon::now()->toCookieString();
     * ```
     *
     * @return string
     */
    public function toCookieString();

    /**
     * @alias toDateTime
     *
     * Return native DateTime PHP object matching the current instance.
     *
     * @example
     * ```
     * var_dump(Carbon::now()->toDate());
     * ```
     *
     * @return DateTime
     */
    public function toDate();

    /**
     * Format the instance as date
     *
     * @example
     * ```
     * echo Carbon::now()->toDateString();
     * ```
     *
     * @return string
     */
    public function toDateString();

    /**
     * Return native DateTime PHP object matching the current instance.
     *
     * @example
     * ```
     * var_dump(Carbon::now()->toDateTime());
     * ```
     *
     * @return DateTime
     */
    public function toDateTime();

    /**
     * Format the instance as date and time T-separated with no timezone
     *
     * @example
     * ```
     * echo Carbon::now()->toDateTimeLocalString();
     * ```
     *
     * @return string
     */
    public function toDateTimeLocalString();

    /**
     * Format the instance as date and time
     *
     * @example
     * ```
     * echo Carbon::now()->toDateTimeString();
     * ```
     *
     * @return string
     */
    public function toDateTimeString();

    /**
     * Format the instance with day, date and time
     *
     * @example
     * ```
     * echo Carbon::now()->toDayDateTimeString();
     * ```
     *
     * @return string
     */
    public function toDayDateTimeString();

    /**
     * Format the instance as a readable date
     *
     * @example
     * ```
     * echo Carbon::now()->toFormattedDateString();
     * ```
     *
     * @return string
     */
    public function toFormattedDateString();

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
    public function toISOString($keepOffset = false);

    /**
     * Return a immutable copy of the instance.
     *
     * @return CarbonImmutable
     */
    public function toImmutable();

    /**
     * Format the instance as ISO8601
     *
     * @example
     * ```
     * echo Carbon::now()->toIso8601String();
     * ```
     *
     * @return string
     */
    public function toIso8601String();

    /**
     * Convert the instance to UTC and return as Zulu ISO8601
     *
     * @example
     * ```
     * echo Carbon::now()->toIso8601ZuluString();
     * ```
     *
     * @return string
     */
    public function toIso8601ZuluString();

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
    public function toJSON();

    /**
     * Return a mutable copy of the instance.
     *
     * @return Carbon
     */
    public function toMutable();

    /**
     * Get the difference in a human readable format in the current locale from an other
     * instance given to now
     *
     * @param int|array $syntax  if array passed, parameters will be extracted from it, the array may contains:
     *                           - 'syntax' entry (see below)
     *                           - 'short' entry (see below)
     *                           - 'parts' entry (see below)
     *                           - 'options' entry (see below)
     *                           - 'join' entry determines how to join multiple parts of the string
     *                           `  - if $join is a string, it's used as a joiner glue
     *                           `  - if $join is a callable/closure, it get the list of string and should return a string
     *                           `  - if $join is an array, the first item will be the default glue, and the second item
     *                           `    will be used instead of the glue for the last item
     *                           `  - if $join is true, it will be guessed from the locale ('list' translation file entry)
     *                           `  - if $join is missing, a space will be used as glue
     *                           if int passed, it add modifiers:
     *                           Possible values:
     *                           - CarbonInterface::DIFF_ABSOLUTE          no modifiers
     *                           - CarbonInterface::DIFF_RELATIVE_TO_NOW   add ago/from now modifier
     *                           - CarbonInterface::DIFF_RELATIVE_TO_OTHER add before/after modifier
     *                           Default value: CarbonInterface::DIFF_ABSOLUTE
     * @param bool      $short   displays short format of time units
     * @param int       $parts   maximum number of parts to display (default value: 1: single part)
     * @param int       $options human diff options
     *
     * @return string
     */
    public function toNow($syntax = null, $short = false, $parts = 1, $options = null);

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
    public function toObject();

    /**
     * Format the instance as RFC1036
     *
     * @example
     * ```
     * echo Carbon::now()->toRfc1036String();
     * ```
     *
     * @return string
     */
    public function toRfc1036String();

    /**
     * Format the instance as RFC1123
     *
     * @example
     * ```
     * echo Carbon::now()->toRfc1123String();
     * ```
     *
     * @return string
     */
    public function toRfc1123String();

    /**
     * Format the instance as RFC2822
     *
     * @example
     * ```
     * echo Carbon::now()->toRfc2822String();
     * ```
     *
     * @return string
     */
    public function toRfc2822String();

    /**
     * Format the instance as RFC3339
     *
     * @example
     * ```
     * echo Carbon::now()->toRfc3339String();
     * ```
     *
     * @return string
     */
    public function toRfc3339String();

    /**
     * Format the instance as RFC7231
     *
     * @example
     * ```
     * echo Carbon::now()->toRfc7231String();
     * ```
     *
     * @return string
     */
    public function toRfc7231String();

    /**
     * Format the instance as RFC822
     *
     * @example
     * ```
     * echo Carbon::now()->toRfc822String();
     * ```
     *
     * @return string
     */
    public function toRfc822String();

    /**
     * Format the instance as RFC850
     *
     * @example
     * ```
     * echo Carbon::now()->toRfc850String();
     * ```
     *
     * @return string
     */
    public function toRfc850String();

    /**
     * Format the instance as RSS
     *
     * @example
     * ```
     * echo Carbon::now()->toRssString();
     * ```
     *
     * @return string
     */
    public function toRssString();

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
    public function toString();

    /**
     * Format the instance as time
     *
     * @example
     * ```
     * echo Carbon::now()->toTimeString();
     * ```
     *
     * @return string
     */
    public function toTimeString();

    /**
     * Format the instance as W3C
     *
     * @example
     * ```
     * echo Carbon::now()->toW3cString();
     * ```
     *
     * @return string
     */
    public function toW3cString();

    /**
     * Create a Carbon instance for today.
     *
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function today($tz = null);

    /**
     * Create a Carbon instance for tomorrow.
     *
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function tomorrow($tz = null);

    /**
     * Translate using translation string or callback available.
     *
     * @param string                                             $key
     * @param array                                              $parameters
     * @param null                                               $number
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     *
     * @return string
     */
    public function translate(string $key, array $parameters = [], $number = null, \Symfony\Component\Translation\TranslatorInterface $translator = null): string;

    /**
     * Translate a time string from a locale to an other.
     *
     * @param string      $timeString time string to translate
     * @param string|null $from       input locale of the $timeString parameter (`Carbon::getLocale()` by default)
     * @param string|null $to         output locale of the result returned (`"en"` by default)
     * @param int         $mode       specify what to translate with options:
     *                                - CarbonInterface::TRANSLATE_ALL (default)
     *                                - CarbonInterface::TRANSLATE_MONTHS
     *                                - CarbonInterface::TRANSLATE_DAYS
     *                                - CarbonInterface::TRANSLATE_UNITS
     *                                - CarbonInterface::TRANSLATE_MERIDIEM
     *                                You can use pipe to group: CarbonInterface::TRANSLATE_MONTHS | CarbonInterface::TRANSLATE_DAYS
     *
     * @return string
     */
    public static function translateTimeString($timeString, $from = null, $to = null, $mode = 15);

    /**
     * Translate a time string from the current locale (`$date->locale()`) to an other.
     *
     * @param string      $timeString time string to translate
     * @param string|null $to         output locale of the result returned ("en" by default)
     *
     * @return string
     */
    public function translateTimeStringTo($timeString, $to = null);

    /**
     * Translate using translation string or callback available.
     *
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param string                                             $key
     * @param array                                              $parameters
     * @param null                                               $number
     *
     * @return string
     */
    public static function translateWith(\Symfony\Component\Translation\TranslatorInterface $translator, string $key, array $parameters = [], $number = null): string;

    /**
     * Format as ->format() do (using date replacements patterns from http://php.net/manual/fr/function.date.php)
     * but translate words whenever possible (months, day names, etc.) using the current locale.
     *
     * @param string $format
     *
     * @return string
     */
    public function translatedFormat(string $format): string;

    /**
     * Set the timezone or returns the timezone name if no arguments passed.
     *
     * @param \DateTimeZone|string $value
     *
     * @return CarbonInterface|string
     */
    public function tz($value = null);

    /**
     * @alias getTimestamp
     *
     * Returns the UNIX timestamp for the current date.
     *
     * @return int
     */
    public function unix();

    /**
     * @alias to
     *
     * Get the difference in a human readable format in the current locale from an other
     * instance given (or now if null given) to current instance.
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
    public function until($other = null, $syntax = null, $short = false, $parts = 1, $options = null);

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather use the ->settings() method.
     *             Or you can use method variants: addMonthsWithOverflow/addMonthsNoOverflow, same variants
     *             are available for quarters, years, decade, centuries, millennia (singular and plural forms).
     * @see settings
     *
     * Indicates if months should be calculated with overflow.
     *
     * @param bool $monthsOverflow
     *
     * @return void
     */
    public static function useMonthsOverflow($monthsOverflow = true);

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather use the ->settings() method.
     * @see settings
     *
     * Enable the strict mode (or disable with passing false).
     *
     * @param bool $strictModeEnabled
     */
    public static function useStrictMode($strictModeEnabled = true);

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather use the ->settings() method.
     *             Or you can use method variants: addYearsWithOverflow/addYearsNoOverflow, same variants
     *             are available for quarters, years, decade, centuries, millennia (singular and plural forms).
     * @see settings
     *
     * Indicates if years should be calculated with overflow.
     *
     * @param bool $yearsOverflow
     *
     * @return void
     */
    public static function useYearsOverflow($yearsOverflow = true);

    /**
     * Set the instance's timezone to UTC.
     *
     * @return static|CarbonInterface
     */
    public function utc();

    /**
     * Returns the minutes offset to UTC if no arguments passed, else set the timezone with given minutes shift passed.
     *
     * @param int|null $offset
     *
     * @return int|static|CarbonInterface
     */
    public function utcOffset(int $offset = null);

    /**
     * Returns the milliseconds timestamps used amongst other by Date javascript objects.
     *
     * @return float
     */
    public function valueOf();

    /**
     * Get/set the week number using given first day of week and first
     * day of year included in the first week. Or use US format if no settings
     * given (Sunday / Jan 6).
     *
     * @param int|null $week
     * @param int|null $dayOfWeek
     * @param int|null $dayOfYear
     *
     * @return int|static
     */
    public function week($week = null, $dayOfWeek = null, $dayOfYear = null);

    /**
     * Set/get the week number of year using given first day of week and first
     * day of year included in the first week. Or use US format if no settings
     * given (Sunday / Jan 6).
     *
     * @param int|null $year      if null, act as a getter, if not null, set the year and return current instance.
     * @param int|null $dayOfWeek first date of week from 0 (Sunday) to 6 (Saturday)
     * @param int|null $dayOfYear first day of year included in the week #1
     *
     * @return int|static|CarbonInterface
     */
    public function weekYear($year = null, $dayOfWeek = null, $dayOfYear = null);

    /**
     * Get/set the weekday from 0 (Sunday) to 6 (Saturday).
     *
     * @param int|null $value new value for weekday if using as setter.
     *
     * @return static|CarbonInterface|int
     */
    public function weekday($value = null);

    /**
     * Get the number of weeks of the current week-year using given first day of week and first
     * day of year included in the first week. Or use US format if no settings
     * given (Sunday / Jan 6).
     *
     * @param int|null $dayOfWeek first date of week from 0 (Sunday) to 6 (Saturday)
     * @param int|null $dayOfYear first day of year included in the week #1
     *
     * @return int
     */
    public function weeksInYear($dayOfWeek = null, $dayOfYear = null);

    /**
     * Create a Carbon instance for yesterday.
     *
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function yesterday($tz = null);

    // </methods>
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon;

use BadMethodCallException;
use Carbon\Traits\Options;
use Closure;
use DateInterval;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;

/**
 * A simple API extension for DateInterval.
 * The implementation provides helpers to handle weeks but only days are saved.
 * Weeks are calculated based on the total days of the current instance.
 *
 * @property int $years Total years of the current interval.
 * @property int $months Total months of the current interval.
 * @property int $weeks Total weeks of the current interval calculated from the days.
 * @property int $dayz Total days of the current interval (weeks * 7 + days).
 * @property int $hours Total hours of the current interval.
 * @property int $minutes Total minutes of the current interval.
 * @property int $seconds Total seconds of the current interval.
 * @property int $microseconds Total microseconds of the current interval.
 * @property int $milliseconds Total microseconds of the current interval.
 * @property-read int $dayzExcludeWeeks Total days remaining in the final week of the current instance (days % 7).
 * @property-read int $daysExcludeWeeks alias of dayzExcludeWeeks
 * @property-read float $totalYears Number of years equivalent to the interval.
 * @property-read float $totalMonths Number of months equivalent to the interval.
 * @property-read float $totalWeeks Number of weeks equivalent to the interval.
 * @property-read float $totalDays Number of days equivalent to the interval.
 * @property-read float $totalDayz Alias for totalDays.
 * @property-read float $totalHours Number of hours equivalent to the interval.
 * @property-read float $totalMinutes Number of minutes equivalent to the interval.
 * @property-read float $totalSeconds Number of seconds equivalent to the interval.
 * @property-read float $totalMilliseconds Number of milliseconds equivalent to the interval.
 * @property-read float $totalMicroseconds Number of microseconds equivalent to the interval.
 * @property-read string $locale locale of the current instance
 *
 * @method static CarbonInterval years($years = 1) Create instance specifying a number of years.
 * @method static CarbonInterval year($years = 1) Alias for years()
 * @method static CarbonInterval months($months = 1) Create instance specifying a number of months.
 * @method static CarbonInterval month($months = 1) Alias for months()
 * @method static CarbonInterval weeks($weeks = 1) Create instance specifying a number of weeks.
 * @method static CarbonInterval week($weeks = 1) Alias for weeks()
 * @method static CarbonInterval days($days = 1) Create instance specifying a number of days.
 * @method static CarbonInterval dayz($days = 1) Alias for days()
 * @method static CarbonInterval day($days = 1) Alias for days()
 * @method static CarbonInterval hours($hours = 1) Create instance specifying a number of hours.
 * @method static CarbonInterval hour($hours = 1) Alias for hours()
 * @method static CarbonInterval minutes($minutes = 1) Create instance specifying a number of minutes.
 * @method static CarbonInterval minute($minutes = 1) Alias for minutes()
 * @method static CarbonInterval seconds($seconds = 1) Create instance specifying a number of seconds.
 * @method static CarbonInterval second($seconds = 1) Alias for seconds()
 * @method static CarbonInterval milliseconds($milliseconds = 1) Create instance specifying a number of milliseconds.
 * @method static CarbonInterval millisecond($milliseconds = 1) Alias for milliseconds()
 * @method static CarbonInterval microseconds($microseconds = 1) Create instance specifying a number of microseconds.
 * @method static CarbonInterval microsecond($microseconds = 1) Alias for microseconds()
 * @method CarbonInterval years($years = 1) Set the years portion of the current interval.
 * @method CarbonInterval year($years = 1) Alias for years().
 * @method CarbonInterval months($months = 1) Set the months portion of the current interval.
 * @method CarbonInterval month($months = 1) Alias for months().
 * @method CarbonInterval weeks($weeks = 1) Set the weeks portion of the current interval.  Will overwrite dayz value.
 * @method CarbonInterval week($weeks = 1) Alias for weeks().
 * @method CarbonInterval days($days = 1) Set the days portion of the current interval.
 * @method CarbonInterval dayz($days = 1) Alias for days().
 * @method CarbonInterval day($days = 1) Alias for days().
 * @method CarbonInterval hours($hours = 1) Set the hours portion of the current interval.
 * @method CarbonInterval hour($hours = 1) Alias for hours().
 * @method CarbonInterval minutes($minutes = 1) Set the minutes portion of the current interval.
 * @method CarbonInterval minute($minutes = 1) Alias for minutes().
 * @method CarbonInterval seconds($seconds = 1) Set the seconds portion of the current interval.
 * @method CarbonInterval second($seconds = 1) Alias for seconds().
 * @method CarbonInterval milliseconds($seconds = 1) Set the seconds portion of the current interval.
 * @method CarbonInterval millisecond($seconds = 1) Alias for seconds().
 * @method CarbonInterval microseconds($seconds = 1) Set the seconds portion of the current interval.
 * @method CarbonInterval microsecond($seconds = 1) Alias for seconds().
 */
class CarbonInterval extends DateInterval
{
    use Options;

    /**
     * Interval spec period designators
     */
    const PERIOD_PREFIX = 'P';
    const PERIOD_YEARS = 'Y';
    const PERIOD_MONTHS = 'M';
    const PERIOD_DAYS = 'D';
    const PERIOD_TIME_PREFIX = 'T';
    const PERIOD_HOURS = 'H';
    const PERIOD_MINUTES = 'M';
    const PERIOD_SECONDS = 'S';

    /**
     * A translator to ... er ... translate stuff
     *
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    protected static $translator;

    /**
     * @var array|null
     */
    protected static $cascadeFactors;

    /**
     * @var array|null
     */
    private static $flipCascadeFactors;

    /**
     * The registered macros.
     *
     * @var array
     */
    protected static $macros = [];

    /**
     * Timezone handler for settings() method.
     *
     * @var mixed
     */
    protected $tzName;

    public function shiftTimezone($tzName)
    {
        $this->tzName = $tzName;

        return $this;
    }

    /**
     * Mapping of units and factors for cascading.
     *
     * Should only be modified by changing the factors or referenced constants.
     *
     * @return array
     */
    public static function getCascadeFactors()
    {
        return static::$cascadeFactors ?: [
            'milliseconds' => [Carbon::MICROSECONDS_PER_MILLISECOND, 'microseconds'],
            'seconds' => [Carbon::MILLISECONDS_PER_SECOND, 'milliseconds'],
            'minutes' => [Carbon::SECONDS_PER_MINUTE, 'seconds'],
            'hours' => [Carbon::MINUTES_PER_HOUR, 'minutes'],
            'dayz' => [Carbon::HOURS_PER_DAY, 'hours'],
            'months' => [Carbon::DAYS_PER_WEEK * Carbon::WEEKS_PER_MONTH, 'dayz'],
            'years' => [Carbon::MONTHS_PER_YEAR, 'months'],
        ];
    }

    private static function standardizeUnit($unit)
    {
        $unit = rtrim($unit, 'sz').'s';

        return $unit === 'days' ? 'dayz' : $unit;
    }

    private static function getFlipCascadeFactors()
    {
        if (!self::$flipCascadeFactors) {
            self::$flipCascadeFactors = [];
            foreach (static::getCascadeFactors() as $to => [$factor, $from]) {
                self::$flipCascadeFactors[self::standardizeUnit($from)] = [self::standardizeUnit($to), $factor];
            }
        }

        return self::$flipCascadeFactors;
    }

    /**
     * Set default cascading factors for ->cascade() method.
     *
     * @param array $cascadeFactors
     */
    public static function setCascadeFactors(array $cascadeFactors)
    {
        self::$flipCascadeFactors = null;
        static::$cascadeFactors = $cascadeFactors;
    }

    ///////////////////////////////////////////////////////////////////
    //////////////////////////// CONSTRUCTORS /////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Create a new CarbonInterval instance.
     *
     * @param int $years
     * @param int $months
     * @param int $weeks
     * @param int $days
     * @param int $hours
     * @param int $minutes
     * @param int $seconds
     * @param int $microseconds
     *
     * @throws \Exception
     */
    public function __construct($years = 1, $months = null, $weeks = null, $days = null, $hours = null, $minutes = null, $seconds = null, $microseconds = null)
    {
        $spec = $years;

        if (!is_string($spec) || floatval($years) || preg_match('/^[0-9.]/', $years)) {
            $spec = static::PERIOD_PREFIX;

            $spec .= $years > 0 ? $years.static::PERIOD_YEARS : '';
            $spec .= $months > 0 ? $months.static::PERIOD_MONTHS : '';

            $specDays = 0;
            $specDays += $weeks > 0 ? $weeks * static::getDaysPerWeek() : 0;
            $specDays += $days > 0 ? $days : 0;

            $spec .= $specDays > 0 ? $specDays.static::PERIOD_DAYS : '';

            if ($hours > 0 || $minutes > 0 || $seconds > 0) {
                $spec .= static::PERIOD_TIME_PREFIX;
                $spec .= $hours > 0 ? $hours.static::PERIOD_HOURS : '';
                $spec .= $minutes > 0 ? $minutes.static::PERIOD_MINUTES : '';
                $spec .= $seconds > 0 ? $seconds.static::PERIOD_SECONDS : '';
            }

            if ($spec === static::PERIOD_PREFIX) {
                // Allow the zero interval.
                $spec .= '0'.static::PERIOD_YEARS;
            }
        }

        parent::__construct($spec);

        if (!is_null($microseconds)) {
            $this->f = $microseconds / Carbon::MICROSECONDS_PER_SECOND;
        }
    }

    /**
     * Returns the factor for a given source-to-target couple.
     *
     * @param string $source
     * @param string $target
     *
     * @return int|null
     */
    public static function getFactor($source, $target)
    {
        $source = self::standardizeUnit($source);
        $target = self::standardizeUnit($target);
        $factors = static::getFlipCascadeFactors();
        if (isset($factors[$source])) {
            [$to, $factor] = $factors[$source];
            if ($to === $target) {
                return $factor;
            }
        }

        return null;
    }

    /**
     * Returns current config for days per week.
     *
     * @return int
     */
    public static function getDaysPerWeek()
    {
        return static::getFactor('dayz', 'weeks') ?: Carbon::DAYS_PER_WEEK;
    }

    /**
     * Returns current config for hours per day.
     *
     * @return int
     */
    public static function getHoursPerDay()
    {
        return static::getFactor('hours', 'dayz') ?: Carbon::HOURS_PER_DAY;
    }

    /**
     * Returns current config for minutes per hour.
     *
     * @return int
     */
    public static function getMinutesPerHour()
    {
        return static::getFactor('minutes', 'hours') ?: Carbon::MINUTES_PER_HOUR;
    }

    /**
     * Returns current config for seconds per minute.
     *
     * @return int
     */
    public static function getSecondsPerMinute()
    {
        return static::getFactor('seconds', 'minutes') ?: Carbon::SECONDS_PER_MINUTE;
    }

    /**
     * Returns current config for microseconds per second.
     *
     * @return int
     */
    public static function getMillisecondsPerSecond()
    {
        return static::getFactor('milliseconds', 'seconds') ?: Carbon::MILLISECONDS_PER_SECOND;
    }

    /**
     * Returns current config for microseconds per second.
     *
     * @return int
     */
    public static function getMicrosecondsPerMillisecond()
    {
        return static::getFactor('microseconds', 'milliseconds') ?: Carbon::MICROSECONDS_PER_MILLISECOND;
    }

    /**
     * Create a new CarbonInterval instance from specific values.
     * This is an alias for the constructor that allows better fluent
     * syntax as it allows you to do CarbonInterval::create(1)->fn() rather than
     * (new CarbonInterval(1))->fn().
     *
     * @param int $years
     * @param int $months
     * @param int $weeks
     * @param int $days
     * @param int $hours
     * @param int $minutes
     * @param int $seconds
     * @param int $microseconds
     *
     * @return static
     */
    public static function create($years = 1, $months = null, $weeks = null, $days = null, $hours = null, $minutes = null, $seconds = null, $microseconds = null)
    {
        return new static($years, $months, $weeks, $days, $hours, $minutes, $seconds, $microseconds);
    }

    /**
     * Get a copy of the instance.
     *
     * @return static
     */
    public function copy()
    {
        $date = new static($this->spec());
        $date->invert = $this->invert;

        return $date;
    }

    /**
     * Provide static helpers to create instances.  Allows CarbonInterval::years(3).
     *
     * Note: This is done using the magic method to allow static and instance methods to
     *       have the same names.
     *
     * @param string $method     magic method name called
     * @param array  $parameters parameters list
     *
     * @return static|null
     */
    public static function __callStatic($method, $parameters)
    {
        $arg = count($parameters) === 0 ? 1 : $parameters[0];

        switch (Carbon::singularUnit(rtrim($method, 'z'))) {
            case 'year':
                return new static($arg);

            case 'month':
                return new static(null, $arg);

            case 'week':
                return new static(null, null, $arg);

            case 'day':
                return new static(null, null, null, $arg);

            case 'hour':
                return new static(null, null, null, null, $arg);

            case 'minute':
                return new static(null, null, null, null, null, $arg);

            case 'second':
                return new static(null, null, null, null, null, null, $arg);

            case 'millisecond':
            case 'milli':
                return new static(null, null, null, null, null, null, null, $arg * Carbon::MICROSECONDS_PER_MILLISECOND);

            case 'microsecond':
            case 'micro':
                return new static(null, null, null, null, null, null, null, $arg);
        }

        if (static::hasMacro($method)) {
            return (new static(0))->$method(...$parameters);
        }

        if (Carbon::isStrictModeEnabled()) {
            throw new BadMethodCallException(sprintf("Unknown fluent constructor '%s'.", $method));
        }

        return null;
    }

    /**
     * Creates a CarbonInterval from string.
     *
     * Format:
     *
     * Suffix | Unit    | Example | DateInterval expression
     * -------|---------|---------|------------------------
     * y      | years   |   1y    | P1Y
     * mo     | months  |   3mo   | P3M
     * w      | weeks   |   2w    | P2W
     * d      | days    |  28d    | P28D
     * h      | hours   |   4h    | PT4H
     * m      | minutes |  12m    | PT12M
     * s      | seconds |  59s    | PT59S
     *
     * e. g. `1w 3d 4h 32m 23s` is converted to 10 days 4 hours 32 minutes and 23 seconds.
     *
     * Special cases:
     *  - An empty string will return a zero interval
     *  - Fractions are allowed for weeks, days, hours and minutes and will be converted
     *    and rounded to the next smaller value (caution: 0.5w = 4d)
     *
     * @param string $intervalDefinition
     *
     * @return static
     */
    public static function fromString($intervalDefinition)
    {
        if (empty($intervalDefinition)) {
            return new static(0);
        }

        $years = 0;
        $months = 0;
        $weeks = 0;
        $days = 0;
        $hours = 0;
        $minutes = 0;
        $seconds = 0;
        $milliseconds = 0;
        $microseconds = 0;

        $pattern = '/(\d+(?:\.\d+)?)\h*([^\d\h]*)/i';
        preg_match_all($pattern, $intervalDefinition, $parts, PREG_SET_ORDER);
        while ([$part, $value, $unit] = array_shift($parts)) {
            $intValue = intval($value);
            $fraction = floatval($value) - $intValue;
            // Fix calculation precision
            switch (round($fraction, 6)) {
                case 1:
                    $fraction = 0;
                    $intValue++;
                    break;
                case 0:
                    $fraction = 0;
                    break;
            }
            switch ($unit === 'µs' ? 'µs' : strtolower($unit)) {
                case 'year':
                case 'years':
                case 'y':
                    $years += $intValue;
                    break;

                case 'month':
                case 'months':
                case 'mo':
                    $months += $intValue;
                    break;

                case 'week':
                case 'weeks':
                case 'w':
                    $weeks += $intValue;
                    if ($fraction) {
                        $parts[] = [null, $fraction * static::getDaysPerWeek(), 'd'];
                    }
                    break;

                case 'day':
                case 'days':
                case 'd':
                    $days += $intValue;
                    if ($fraction) {
                        $parts[] = [null, $fraction * static::getHoursPerDay(), 'h'];
                    }
                    break;

                case 'hour':
                case 'hours':
                case 'h':
                    $hours += $intValue;
                    if ($fraction) {
                        $parts[] = [null, $fraction * static::getMinutesPerHour(), 'm'];
                    }
                    break;

                case 'minute':
                case 'minutes':
                case 'm':
                    $minutes += $intValue;
                    if ($fraction) {
                        $parts[] = [null, $fraction * static::getSecondsPerMinute(), 's'];
                    }
                    break;

                case 'second':
                case 'seconds':
                case 's':
                    $seconds += $intValue;
                    if ($fraction) {
                        $parts[] = [null, $fraction * static::getMillisecondsPerSecond(), 'ms'];
                    }
                    break;

                case 'millisecond':
                case 'milliseconds':
                case 'milli':
                case 'ms':
                    $milliseconds += $intValue;
                    if ($fraction) {
                        $microseconds += round($fraction * static::getMicrosecondsPerMillisecond());
                    }
                    break;

                case 'microsecond':
                case 'microseconds':
                case 'micro':
                case 'µs':
                    $microseconds += $intValue;
                    break;

                default:
                    throw new InvalidArgumentException(
                        sprintf('Invalid part %s in definition %s', $part, $intervalDefinition)
                    );
            }
        }

        return new static($years, $months, $weeks, $days, $hours, $minutes, $seconds, $milliseconds * Carbon::MICROSECONDS_PER_MILLISECOND + $microseconds);
    }

    /**
     * Create a CarbonInterval instance from a DateInterval one.  Can not instance
     * DateInterval objects created from DateTime::diff() as you can't externally
     * set the $days field.
     *
     * @param DateInterval $di
     *
     * @return static
     */
    public static function instance(DateInterval $di)
    {
        $microseconds = $di->f;
        $instance = new static(static::getDateIntervalSpec($di));
        if ($microseconds) {
            $instance->f = $microseconds;
        }
        $instance->invert = $di->invert;
        foreach (['y', 'm', 'd', 'h', 'i', 's'] as $unit) {
            if ($di->$unit < 0) {
                $instance->$unit *= -1;
            }
        }

        return $instance;
    }

    /**
     * Make a CarbonInterval instance from given variable if possible.
     *
     * Always return a new instance. Parse only strings and only these likely to be intervals (skip dates
     * and recurrences). Throw an exception for invalid format, but otherwise return null.
     *
     * @param mixed $var
     *
     * @return static|null
     */
    public static function make($var)
    {
        if ($var instanceof DateInterval) {
            return static::instance($var);
        }

        if (!is_string($var)) {
            return null;
        }

        $var = trim($var);

        if (preg_match('/^P[T0-9]/', $var)) {
            return new static($var);
        }

        if (preg_match('/^(?:\h*\d+(?:\.\d+)?\h*[a-z]+)+$/i', $var)) {
            return static::fromString($var);
        }

        /** @var static $interval */
        $interval = static::createFromDateString($var);

        return $interval->isEmpty() ? null : $interval;
    }

    /**
     * Sets up a DateInterval from the relative parts of the string.
     *
     * @param string $time
     *
     * @return static
     *
     * @link http://php.net/manual/en/dateinterval.createfromdatestring.php
     */
    public static function createFromDateString($time)
    {
        $interval = parent::createFromDateString($time);
        if ($interval instanceof DateInterval && !($interval instanceof static)) {
            $interval = static::instance($interval);
        }

        return static::instance($interval);
    }

    ///////////////////////////////////////////////////////////////////
    ///////////////////////// GETTERS AND SETTERS /////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Get a part of the CarbonInterval object.
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return int|float|string
     */
    public function __get($name)
    {
        if (substr($name, 0, 5) === 'total') {
            return $this->total(substr($name, 5));
        }

        switch ($name) {
            case 'years':
                return $this->y;

            case 'months':
                return $this->m;

            case 'dayz':
                return $this->d;

            case 'hours':
                return $this->h;

            case 'minutes':
                return $this->i;

            case 'seconds':
                return $this->s;

            case 'milli':
            case 'milliseconds':
                return (int) floor(round($this->f * Carbon::MICROSECONDS_PER_SECOND) / Carbon::MICROSECONDS_PER_MILLISECOND);

            case 'micro':
            case 'microseconds':
                return (int) round($this->f * Carbon::MICROSECONDS_PER_SECOND);

            case 'weeks':
                return (int) floor($this->d / static::getDaysPerWeek());

            case 'daysExcludeWeeks':
            case 'dayzExcludeWeeks':
                return $this->d % static::getDaysPerWeek();

            case 'locale':
                return $this->getLocalTranslator()->getLocale();

            default:
                throw new InvalidArgumentException(sprintf("Unknown getter '%s'", $name));
        }
    }

    /**
     * Set a part of the CarbonInterval object.
     *
     * @param string $name
     * @param int    $value
     *
     * @throws \InvalidArgumentException
     */
    public function __set($name, $value)
    {
        switch (Carbon::singularUnit(rtrim($name, 'z'))) {
            case 'year':
                $this->y = $value;
                break;

            case 'month':
                $this->m = $value;
                break;

            case 'week':
                $this->d = $value * static::getDaysPerWeek();
                break;

            case 'day':
                $this->d = $value;
                break;

            case 'hour':
                $this->h = $value;
                break;

            case 'minute':
                $this->i = $value;
                break;

            case 'second':
                $this->s = $value;
                break;

            case 'milli':
            case 'millisecond':
                $this->microseconds = $value * Carbon::MICROSECONDS_PER_MILLISECOND + $this->microseconds % Carbon::MICROSECONDS_PER_MILLISECOND;
                break;

            case 'micro':
            case 'microsecond':
                $this->f = $value / Carbon::MICROSECONDS_PER_SECOND;
                break;

            default:
                if ($this->localStrictModeEnabled ?? Carbon::isStrictModeEnabled()) {
                    throw new InvalidArgumentException(sprintf("Unknown setter '%s'", $name));
                }

                $this->$name = $value;
        }
    }

    /**
     * Allow setting of weeks and days to be cumulative.
     *
     * @param int $weeks Number of weeks to set
     * @param int $days  Number of days to set
     *
     * @return static
     */
    public function weeksAndDays($weeks, $days)
    {
        $this->dayz = ($weeks * static::getDaysPerWeek()) + $days;

        return $this;
    }

    /**
     * Returns true if the interval is empty for each unit.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->years === 0 &&
            $this->mon