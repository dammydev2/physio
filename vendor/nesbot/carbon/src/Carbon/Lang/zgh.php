'o',
            // @property int
            // @call isSameUnit
            'month' => 'n',
            // @property int
            'day' => 'j',
            // @property int
            'hour' => 'G',
            // @property int
            'minute' => 'i',
            // @property int
            'second' => 's',
            // @property int
            'micro' => 'u',
            // @property int
            'microsecond' => 'u',
            // @property-read int 0 (for Sunday) through 6 (for Saturday)
            'dayOfWeek' => 'w',
            // @property-read int 1 (for Monday) through 7 (for Sunday)
            'dayOfWeekIso' => 'N',
            // @property-read int ISO-8601 week number of year, weeks starting on Monday
            'weekOfYear' => 'W',
            // @property-read int number of days in the given month
            'daysInMonth' => 't',
            // @property int seconds since the Unix Epoch
            'timestamp' => 'U',
            // @property-read string "am"/"pm" (Ante meridiem or Post meridiem latin lowercase mark)
            'latinMeridiem' => 'a',
            // @property-read string "AM"/"PM" (Ante meridiem or Post meridiem latin uppercase mark)
            'latinUpperMeridiem' => 'A',
            // @property string the day of week in English
            'englishDayOfWeek' => 'l',
            // @property string the abbreviated day of week in English
            'shortEnglishDayOfWeek' => 'D',
            // @property string the day of week in English
            'englishMonth' => 'F',
            // @property string the abbreviated day of week in English
            'shortEnglishMonth' => 'M',
            // @property string the day of week in current locale LC_TIME
            'localeDayOfWeek' => '%A',
            // @property string the abbreviated day of week in current locale LC_TIME
            'shortLocaleDayOfWeek' => '%a',
            // @property string the month in current locale LC_TIME
            'localeMonth' => '%B',
            // @property string the abbreviated month in current locale LC_TIME
            'shortLocaleMonth' => '%b',
        ];

        switch (true) {
            case isset($formats[$name]):
                $format = $formats[$name];
                $method = substr($format, 0, 1) === '%' ? 'formatLocalized' : 'rawFormat';
                $value = $this->$method($format);

                return is_numeric($value) ? (int) $value : $value;

            // @property-read string long name of weekday translated according to Carbon locale, in english if no translation available for current language
            case $name === 'dayName':
                return $this->getTranslatedDayName();
            // @property-read string short name of weekday translated according to Carbon locale, in english if no translation available for current language
            case $name === 'shortDayName':
                return $this->getTranslatedShortDayName();
            // @property-read string very short name of weekday translated according to Carbon locale, in english if no translation available for current language
            case $name === 'minDayName':
                return $this->getTranslatedMinDayName();
            // @property-read string long name of month translated according to Carbon locale, in english if no translation available for current language
            case $name === 'monthName':
                return $this->getTranslatedMonthName();
            // @property-read string short name of month translated according to Carbon locale, in english if no translation available for current language
            case $name === 'shortMonthName':
          