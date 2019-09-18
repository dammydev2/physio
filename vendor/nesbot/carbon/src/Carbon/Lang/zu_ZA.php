oduce a relative date.
     *
     * @param string $time
     *
     * @return bool true if time match a relative date, false if absolute or invalid time string
     */
    public static function hasRelativeKeywords($time)
    {
        if (strtotime($time) === false) {
            return false;
        }

        $date1 = new DateTime('2000-01-01T00:00:00Z');
        $date1->modify($time);
        $date2 = new DateTime('2001-12-25T00:00:00Z');
        $date2->modify($time);

        return $date1 != $date2;
    }

    ///////////////////////////////////////////////////////////////////
    /////////////////////// STRING FORMATTING /////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather use UTF-8 language packages on every machine.
     *
     * Set if UTF8 will be used for localized date/time.
     *
     * @param bool $utf8
     */
    public static function setUtf8($utf8)
    {
        static::$utf8 = $utf8;
    }

    /**
     * Format the instance with the current locale.  You can set the current
     * locale using setlocale() http://php.net/setlocale.
     *
     * @param string $format
     *
     * @return string
     */
    public function formatLocalized($format)
    {
        // Check for Windows to find and replace the %e modifier correctly.
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format); // @codeCoverageIgnore
        }

        $formatted = strftime($format, strtotime($this->toDateTimeString()));

        return static::$utf8 ? utf8_encode($formatted) : $formatted;
    }

    /**
     * Returns list of locale formats 