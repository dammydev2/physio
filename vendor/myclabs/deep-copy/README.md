                                                                   1 (for Monday) through 7 (for Sunday)
 * @property-read int            $weekOfYear                                                                         ISO-8601 week number of year, weeks starting on Monday
 * @property-read int            $daysInMonth                                                                        number of days in the given month
 * @property-read string         $latinMeridiem                                                                      "am"/"pm" (Ante meridiem or Post meridiem latin lowercase mark)
 * @property-read string         $latinUpperMeridiem                                                                 "AM"/"PM" (Ante meridiem or Post meridiem latin uppercase mark)
 * @property-read string         $dayName                                                                            long name of weekday translated according to Carbon locale, in english if no translation available for current language
 * @property-read string         $shortDayName                                                                       short name of weekday translated according to Carbon locale, in english if no translation available for current language
 * @property-read string         $minDayName                                                                         very short name of weekday translated according to Carbon locale, in english if no translation available for current language
 * @property-read string         $monthName                                                                          long name of month translated according to Carbon locale, in english if no translation available for current language
 * @property-read string         $shortMonthName                                                                     short name of month translated according to Carbon locale, in english if no translation available for current language
 * @property-read string         $meridiem                                                                           lowercase meridiem mark translated according to Carbon locale, in latin if no translation available for current language
 * @property-read string         $upperMeridiem                                                                      uppercase meridiem mark translated according to Carbon locale, in latin if no translation available for current language
 * @property-read int            $noZeroHour                                                                         current hour from 1 to 24
 * @property-read int            $weeksInYear                                                                        51 through 53
 * @property-read int            $isoWeeksInYear                                                                     51 through 53
 * @property-read int            $weekOfMonth                                                                        1 through 5
 * @property-read int            $weekNumberInMonth                                                                  1 through 5
 * @property-read int            $firstWeekDay                                                                       0 through 6
 * @property-read int            $lastWeekDay                                                                        0 through 6
 * @property-read int            $daysInYear                                                                         365 or 366
 * @property-read int            $quarter                                                                            the quarter of this instance, 1 - 4
 * @property-read int            $decade                                                                             the decade of this instance
 * @property-read int            $century                                                                            the century of this instance
 * @property-read int            $millennium                                                                         the millennium of this instance
 * @property-read bool           $dst                                                                                daylight savings time indicator, true if DST, false otherwise
 * @property-read bool           $local                                                                              checks if the timezone is local, true if local, false otherwise
 * @property-read bool           $utc                                                                                checks if the timezone is UTC, true if UTC, false otherwise
 * @property-read string         $timezoneName                                                                       the current timezone name
 * @property-read string         $tzName                                                                             alias of $timezoneName
 * @property-read string         $timezoneAbbreviatedName                                                            the current timezone abbreviated name
 * @property-read string         $tzAbbrName                                                                         alias of $timezoneAbbreviatedName
 * @property-read string         $locale                                                                             locale of the current instance
 *
 * @method        bool           isUtc()                                                                             Check if the current instance has UTC timezone. (Both isUtc and isUTC cases are valid.)
 * @method        bool           isLocal()                                                                           Check if the current instance has non-UTC timezone.
 * @method        bool           isValid()                                                                           Check if the current instance is a valid date.
 * @method        bool           isDST()                                                                             Check if the current instance is in a daylight saving time.
 * @method        bool           isSunday()                                                                          Checks if the instance day is sunday.
 * @method        bool           isMonday()                                                                          Checks if the instance day is monday.
 * @method        bool           isTuesday()                                                                         Checks if the instance day is tuesday.
 * @method        bool           isWednesday()                                                                       Checks if the instance day is wednesday.
 * @method        bool           isThursday()                                                                        Checks if the instance day is thursday.
 * @method        bool           isFriday()                                                                          Checks if the instance day is friday.
 * @method        bool           isSaturday()                                                                        Checks if the instance day is saturday.
 * @method        bool           isSameYear(\DateTimeInterface $date = null)                                         Checks if the given date is in the same year as the instance. If null passed, compare to now (with the same timezone).
 * @method        bool           isCurrentYear()                                                                     Checks if the instance is in the same year as the current moment.
 * @method        bool           isNextYear()                                                                        Checks if the instance is in the same year as the current moment next year.
 * @method        bool           isLastYear()                                                                        Checks if the instance is in the same year as the current moment last year.
 * @method        bool           isSameWeek(\DateTimeInterface $date = null)                                         Checks if the given date is in the same week as the instance. If null passed, compare to now (with the same timezone).
 * @method        bool           isCurrentWeek()                                                                     Checks if the instance is in the same week as the current moment.
 * @method        bool           isNextWeek()                                                                        Checks if the instance is in the same week as the current moment next week.
 * @method        bool           isLastWeek()                                                                        Checks if the instance is in the same week as the current moment last week.
 * @method        bool           isSameDay(\DateTimeInterface $date = null)                                          Checks if the given date is in the same day as the instance. If null passed, compare to now (with the same timezone).
 * @method        bool           isCurrentDay()                                                                      Checks if the instance is in the same day as the current moment.
 * @method        bool           isNextDay()                                                                         Checks if the instance is in the same day as the current moment next day.
 * @method        bool           isLastDay()                                                                         Checks if the instance is in the same day as the current moment last day.
 * @method        bool           isSameHour(\DateTimeInterface $date = null)                                         Checks if the given date is in the same hour as the instance. If null passed, compare to now (with the same timezone).
 *