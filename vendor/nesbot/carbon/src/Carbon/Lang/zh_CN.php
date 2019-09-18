getOffset();

            // @property int the timezone offset in minutes from UTC
            case $name === 'offsetMinutes':
                return $this->getOffset() / static::SECONDS_PER_MINUTE;

            // @property int the timezone offset in hours from UTC
            case $name === 'offsetHours':
                return $this->getOffset() / static::SECONDS_PER_MINUTE / static::MINUTES_PER_HOUR;

            // @property-read bool daylight savings time indicator, true if DST, false otherwise
            case $name === 'dst':
                return $this->rawFormat('I') === '1';

            // @property-read bool checks if the timezone is local, true if local, false otherwise
            case $name === 'local':
                return $this->getOffset() === $this->copy()->setTimezone(date_default_timezone_get())->getOffset();

            // @property-read bool checks if the timezone is UTC, true if UTC, false otherwise
            case $name === 'utc':
                return $this->getOffset() === 0;

            // @property CarbonTimeZone $timezone the current timezone
            // @property CarbonTimeZone $tz alias of $timezone
            case $name === 'timezone' || $name === 'tz':
                return CarbonTimeZone::instance($this->getTimezone());

            // @property-read string $timezoneName the current timezone name
            // @property-read string $tzName alias of $timezoneName
            case $name === 'timezoneName' || $name === 'tzName':
                return $this->getTimezone()->getName();

            // @property-read string $timezoneAbbreviatedName the current timezone abbreviated name
            // @property-read string $tzAbbrName alias of $timezoneAbbreviatedName
            case $name === 'timezoneAbbreviatedName' || $name === 'tzAbbrName':
                return CarbonTimeZone::instance($this->getTimezone())->getAbbr($this->dst);

            // @property-read string locale of the current instance
            case $name === 'locale':
                return $this->getLocalTranslator()->getLocale();

            default:
                if (static::hasMacro($macro = 'get'.ucfirst($name))) {
                    return $this->$macro();
                }

                throw new InvalidArgumentException(sprintf("Unknown getter '%s'", $name));
        }
    }

    /**
     * Check if an attribute exists on the object
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        try {
            $this->__get($name);
        } catch (InvalidArgumentException | ReflectionException $e) {
            return false;
        }

        return true;
    }

    /**
     * Set a part of the Carbon object
     *
     * @param string                   $name
     * @param string|int|\DateTimeZone $value
     *
     * @throws InvalidArgumentException|ReflectionException
     *
     * 