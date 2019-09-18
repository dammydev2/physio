 bool           $dst                                                                                daylight savings time indicator, true if DST, false otherwise
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
 * @method        bool           isSameWeek(\DateTimeInterface $date = null)                                         Checks if the given date is in the same week as the 