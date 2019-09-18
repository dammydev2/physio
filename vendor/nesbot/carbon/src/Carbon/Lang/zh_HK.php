 += static::MICROSECONDS_PER_SECOND;
                }
                while ($value >= static::MICROSECONDS_PER_SECOND) {
                    $this->addSecond();
                    $value -= static::MICROSECONDS_PER_SECOND;
                }
                $this->modify($this->rawFormat('H:i:s.').str_pad(round($value), 6, '0', STR_PAD_LEFT));
                break;

            case 'year':
            case 'month':
            case 'day':
            case 'hour':
            case 'minute':
            case 'second':
                [$year, $month, $day, $hour, $minute, $second] = explode('-', $this->rawFormat('Y-n-j-G-i-s'));
                $$name = $value;
                $this->setDateTime($year, $month, $day, $hour, $minute, $second);
                break;

            case 'week':
                return $this->week($value);

            case 'isoWeek':
                return $this->isoWeek($value);

            case 'weekYear':
                return $this->weekYear($value);

            case 'isoWeekYear':
                return $this->isoWeekYear($value);

            case 'dayOfYear':
                return $this->addDays($value - $this->dayOfYear);

            case 'timestamp':
                parent::setTimestamp($value);
                break;

            case 'offset':
                $this->setTimezone(static::safeCreateDateTimeZone($value / static::SECONDS_PER_MINUTE / static::MINUTES_PER_HOUR));
                break;

            case 'offsetMinutes':
                $this->setTimezone(static::safeCreateDateTimeZone($value / static::MINUTES_PER_HOUR));
                break;

            case 'offsetHours':
                $this->setTimezone(static::safeCreateDateTimeZone($value));
                break;

            case 'timezone':
            case 'tz':
                $this->setTimezone($value);
                break;

            default:
                if (static::hasMacro($macro = 'set'.ucfirst($name))) {
                    $this->$macro($value);

                    break;
                }

                if ($this->localStrictModeEnabled ?? static::isStrictModeEnabled()) {
                    throw new InvalidArgumentException(sprintf("Unknown setter '%s'", $name));
                }

                $this->$name = $value;
        }

        return $this;
    }

    protected function getTranslatedFormByRegExp($baseKey, $keySuffix, $context, $subKey, $defaultValue)
    {
        $key = $baseKey.$keySuffix;
        $standaloneKey = "${key}_standalone";
        $baseTranslation = $this->getTranslationMessage($key);

        if ($baseTranslation instanceof Closure) {
            return $baseTranslation($this, $context, $subKey) ?: $defaultValue;
        }

        if (
            $this->getTranslationMessage("$standaloneKey.$subKey") &&
            (!$context || ($regExp = $this->getTranslationMessage("${baseKey}_regexp")) && !preg_match($regExp, $context))
        ) {
            $key = $standal