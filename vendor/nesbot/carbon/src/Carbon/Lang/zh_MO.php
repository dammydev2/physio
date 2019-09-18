**
     * Get the translation of the current abbreviated week day name (with context for languages with multiple forms).
     *
     * @param string|null $context whole format string
     *
     * @return string
     */
    public function getTranslatedMinDayName($context = null)
    {
        return $this->getTranslatedDayName($context, '_min', $this->shortEnglishDayOfWeek);
    }

    /**
     * Get the translation of the current month day name (with context for languages with multiple forms).
     *
     * @param string|null $context      whole format string
     * @param string      $keySuffix    "" or "_short"
     * @param string|null $defaultValue default value if translation missing
     *
     * @return string
     */
    public function getTranslatedMonthName($context = null, $keySuffix = '', $defaultValue = null)
    {
        return $this->getTranslatedFormByRegExp('months', $keySuffix, $context, $this->month - 1, $defaultValue ?: $this->englishMonth);
    }

    /**
     * Get the translation of the current short month day name (with context for languages with multiple forms).
     *
     * @param string|null $context whole format string
     *
     * @return string
     */
    public function getTranslatedShortMonthName($context = null)
    {
        return $this->getTranslatedMonthName($context, '_short', $this->shortEnglishMonth);
    }

    /**
     * Get/set the day of year.
     *
     * @param int|null $value new value for day of year if using as setter.
     *
     * @return static|CarbonInterface|int
     */
    public function dayOfYear($value = null)
    {
        $dayOfYear = $this->dayOfYear;

        return is_null($value) ? $dayOfYear : $this->addDays($value - $dayOfYear);
    }

    /**
     * Get/set the weekday from 0 (Sunday) to 6 (Saturday).
     *
     * @param int|null $value new value for weekday if using as setter.
     *
     * @return static|CarbonInterface|int
     */
    public function weekday($value = null)
    {
        $dayOfWeek = ($this->dayOfWeek + 7 - intval($this->getTranslationMessage('first_day_of_week') ?? 0)) % 7;

        return is_null($value) ? $dayOfWeek : $this->addDays($value - $dayOfWeek);
    }

    /**
     * Get/set the ISO weekday from 1 (Monday) to 7 (Sunday).
     *
     * @param int|null $value new value for weekday if using as setter.
     *
     * @return static|CarbonInterface|int
     */
    public function isoWeekday($value = null)
    {
        $dayOfWeekIso = $this->dayOfWeekIso;

        return is_null($value) ? $dayOfWeekIso : $this->addDays($value - $dayOfWeekIso);
    }

    /**
     * Set any unit to a new value without overflowing current other unit given.
     *
     * @param string $valueUnit    unit name to modify
     * @param int    $value        new value for the input unit
     * @para