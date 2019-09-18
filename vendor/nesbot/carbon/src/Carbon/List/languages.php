=> ['getPaddedUnit', ['isoWeekYear', 5]],
                'g' => 'weekYear',
                'gg' => ['getPaddedUnit', ['weekYear']],
                'ggg' => ['getPaddedUnit', ['weekYear', 3]],
                'gggg' => ['getPaddedUnit', ['weekYear', 4]],
                'ggggg' => ['getPaddedUnit', ['weekYear', 5]],
                'W' => 'isoWeek',
                'WW' => ['getPaddedUnit', ['isoWeek']],
                'Wo' => ['ordinal', ['isoWeek', 'W']],
                'w' => 'week',
                'ww' => ['getPaddedUnit', ['week']],
                'wo' => ['ordinal', ['week', 'w']],
                'x' => ['valueOf', []],
                'X' => 'timestamp',
                'Y' => 'year',
                'YY' => ['rawFormat', ['y']],
                'YYYY' => ['getPaddedUnit', ['year', 4]],
                'YYYYY' => ['getPaddedUnit', ['year', 5]],
                'YYYYYY' => function (CarbonInterface $date) {
                    return ($date->year < 0 ? '' : '+').$date->getPaddedUnit('year', 6);
                },
                'z' => 'tzAbbrName',
                'zz' => 'tzName',
                'Z' => ['getOffsetString', []],
                'ZZ' => ['getOffsetString', ['']],
            ];
        }

        return $units;
    }

    /**
     * Returns a unit of the instance padded with 0 by default or any other string if specified.
     *
     * @param string $unit      Carbon unit name
     * @param int    $length    Length of the output (2 by default)
     * @param string $padString String to use for padding ("0" by default)
     * @param int    $padType   Side(s) to pad (STR_PAD_LEFT by default)
     *
     * @return string
     */
    public function getPaddedUnit($unit, $length = 2, $padString = '0', $padType = STR_PAD_LEFT)
    {
        return ($this->$unit < 0 ? '-' : '').str_pad(abs($this->$unit), $length, $padString, $padType);
    }

    /**
     * Return a property with its ordinal.
     *
     * @param string      $key
     * @param string|null $period
     *
     * @return string
     */
    public function ordinal(string $key, string $period = null): string
    {
        $number = $this->$key;
        $result = $this->translate('ordinal', [
            ':number' => $number,
            ':period' => $period,
        ]);

        return strval($result === 'ordinal' ? $number : $result);
    }

    /**
     * Return the meridiem of the current time in the current locale.
     *
     * @param bool $isLower if true, returns lowercase variant if available in the current locale.
     *
     * @return string
     */
    public function meridiem(bool $isLower = false): string
    {
        $hour = $this->hour;
        $index = $hour < 12 ? 0 : 1;

        if ($isLower) {
            $key = 'meridiem.'.($index + 2);
            $result = $this->translate($key);

            if ($result !== $key) {
                return $result;
            }
        }

        $key = "meridiem.$index";
        $result = $this->translate($key);
        if ($result === $key) {
            $result = $this->translate('meridiem', [
                ':hour' => $this->hour,
                ':minute' => $this->minute,
                ':isLower' => $isLower,
            ]);

            if ($result === 'meridiem') {
                return $isLower ? $this->latinMeridiem : $this->latinUpperMeridiem;
            }
        } elseif ($isLower) {
            $result = mb_strtolower($result);
        }

        return $result;
    }

    /**
     * Returns the alternative number if available in the current locale.
     *
     * @param string $key date property
     *
     * @return string
     */
    public function getAltNumber(string $key): string
    {
        $number = strlen($key) > 1 ? $this->$key : $this->rawFormat('h');
        $translateKey = "alt_numbers.$number";
        $symbol = $this->translate($translateKey);

        if ($symbol !== $translateKey) {
            return $symbol;
        }

        if ($number > 99 && $this->translate('alt_numbers.99') !== 'alt_numbers.99') {
            $start = '';
            foreach ([10000, 1000, 100] as $exp) {
                $key = "alt_numbers_pow.$exp";
                if ($number >= $exp && $number < $exp * 10 && ($pow = $this->translate($key)) !== $key) {
                    $unit = floor($number / $exp);
                    $number -= $unit * $exp;
                    $start .= ($unit > 1 ? $this->translate("alt_numbers.$unit") : '').$pow;
                }
            }
            $result = '';
            while ($number) {
                $chunk = $number % 100;
                $result = $this->translate("alt_numbers.$chunk").$result;
                $number = floor($number / 100);
            }

            return "$start$result";
        }

        if ($number > 9 && $this->translate('alt_numbers.9') !== 'alt_numbers.9') {
            $result = '';
            while ($number) {
                $chunk = $number % 10;
                $result = $this->translate("alt_numbers.$chunk").$result;
                $number = floor($number / 10);
            }

            return $result;
        }

        return $number;
    }

    /**
     * Format in the current language using ISO replacement patterns.
     *
     * @param string      $format
     * @param string|null $originalFormat provide context if a chunk has been passed alone
     *
     * @return string
     */
    public function isoFormat(string $format, string $originalFormat = null): string
    {
        $result = '';
        $length = mb_strlen($format);
        $originalFormat = $originalFormat ?: $format;
        $inEscaped = false;
        $formats = null;
        $units = null;

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($format, $i, 1);

            if ($char === '\\') {
                $result .= mb_substr($format, ++$i, 1);

                continue;
            }

            if ($char === '[' && !$inEscaped) {
                $inEscaped = true;

                continue;
            }

            if ($char === ']' && $inEscaped) {
                $inEscaped = false;

                continue;
            }

            if ($inEscaped) {
                $result .= $char;

                continue;
            }

            $input = mb_substr($format, $i);

            if (preg_match('/^(LTS|LT|[Ll]{1,4})/', $input, $match)) {
                if ($formats === null) {
                    $formats = $this->getIsoFormats();
                }

                $code = $match[0];
                $sequence = $formats[$code] ?? preg_replace_callback(
                    '/MMMM|MM|DD|dddd/',
                    function ($code) {
                        return mb_substr($code[0], 1);
                    },
                    $formats[strtoupper($code)] ?? ''
                );
                $rest = mb_substr($format, $i + mb_strlen($code));
                $format = mb_substr($format, 0, $i).$sequence.$rest;
                $length = mb_strlen($format);
                $input = $sequence.$rest;
            }

            if (preg_match('/^'.CarbonInterface::ISO_FORMAT_REGEXP.'/', $input, $match)) {
                $code = $match[0];

                if ($units === null) {
                    $units = static::getIsoUnits();
                }

                $sequence = $units[$code] ?? '';

                if ($sequence instanceof Closure) {
                    $sequence = $sequence($this, $originalFormat);
                } elseif (is_array($sequence)) {
                    try {
                        $sequence = $this->{$sequence[0]}(...$sequence[1]);
                    } catch (ReflectionException | InvalidArgumentException | BadMethodCallException $e) {
                        $sequence = '';
                    }
                } elseif (is_string($sequence)) {
                    $sequence = $this->$sequence ?? $code;
                }

                $format = mb_substr($format, 0, $i).$sequence.mb_substr($format, $i + mb_strlen($code));
                $i += mb_strlen($sequence) - 1;
                $length = mb_strlen($format);
                $char = $sequence;
            }

            $result .= $char;
        }

        return $result;
    }

    /**
     * List of replacements from date() format to isoFormat().
     *
     * @return array
     */
    public static function getFormatsToIsoReplacements()
    {
        static $replacements = null;

        if ($replacements === null) {
            $replacements = [
                'd' => true,
                'D' => 'ddd',
                'j' => true,
                'l' => 'dddd',
                'N' => true,
                'S' => function ($date) {
                    $day = $date->rawFormat('j');

                    return str_replace("$day", '', $date->isoFormat('Do'));
                },
                'w' => true,
                'z' => true,
                'W' => true,
                'F' => 'MMMM',
                'm' => true,
                'M' => 'MMM',
                'n' => true,
                't' => true,
                'L' => true,
                'o' => true,
                'Y' => true,
                'y' => true,
                'a' => 'a',
                'A' => 'A',
                'B' => true,
                'g' => true,
                'G' => true,
                'h' => true,
                'H' => true,
                'i' => true,
                's' => true,
                'u' => true,
                'v' => true,
                'E' => true,
                'I' => true,
                'O' => true,
                'P' => true,
                'Z' => true,
                'c' => true,
                'r' => true,
                'U' => true,
            ];
        }

        return $replacements;
    }

    /**
     * Format as ->format() do (using date replacements patterns from http://php.net/manual/fr/function.date.php)
     * but translate words whenever possible (months, day names, etc.) using the current locale.
     *
     * @param string $format
     *
     * @return string
     */
    public function translatedFormat(string $format): string
    {
        $replacements = static::getFormatsToIsoReplacements();
        $context = '';
        $isoFormat = '';
        $length = mb_strlen($format);

        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($format, $i, 1);

            if ($char === '\\') {
                $replacement = mb_substr($format, $i, 2);
                $isoFormat .= $replacement;
                $i++;

                continue;
            }

            if (!isset($replacements[$char])) {
                $replacement = preg_match('/^[A-Za-z]$/', $char) ? "\\$char" : $char;
                $isoFormat .= $replacement;
                $context .= $replacement;

                continue;
            }

            $replacement = $replacements[$char];

            if ($replacement === true) {
                static $contextReplacements = null;

                if ($contextReplacements === null) {
                    $contextReplacements = [
                        'm' => 'MM',
                        'd' => 'DD',
                        't' => 'D',
                        'j' => 'D',
                        'N' => 'e',
                        'w' => 'e',
                        'n' => 'M',
                        'o' => 'YYYY',
                        'Y' => 'YYYY',
                        'y' => 'YY',
                        'g' => 'h',
                        'G' => 'H',
                        'h' => 'hh',
                        'H' => 'HH',
                        'i' => 'mm',
                        's' => 'ss',
                    ];
                }

                $isoFormat .= '['.$this->rawFormat($char).']';
                $context .= $contextReplacements[$char] ?? ' ';

                continue;
            }

            if ($replacement instanceof Closure) {
                $replacement = '['.$replacement($this).']';
                $isoFormat .= $replacement;
                $context .= $replacement;

                continue;
            }

            $isoFormat .= $replacement;
            $context .= $replacement;
        }

        return $this->isoFormat($isoFormat, $context);
    }

    /**
     * Returns the offset hour and minute formatted with +/- and a given separator (":" by default).
     * For example, if the time zone is 9 hours 30 minutes, you'll get "+09:30", with "@@" as first
     * argument, "+09@@30", with "" as first argument, "+0930". Negative offset will return something
     * like "-12:00".
     *
     * @param string $separator string to place between hours and minutes (":" by default)
     *
     * @return string
     */
    public function getOffsetString($separator = ':')
    {
        $second = $this->getOffset();
        $symbol = $second < 0 ? '-' : '+';
        $minute = abs($second) / static::SECONDS_PER_MINUTE;
        $hour = str_pad(floor($minute / static::MINUTES_PER_HOUR), 2, '0', STR_PAD_LEFT);
        $minute = str_pad($minute % static::MINUTES_PER_HOUR, 2, '0', STR_PAD_LEFT);

        return "$symbol$hour$separator$minute";
    }

    protected static function executeStaticCallable($macro, ...$parameters)
    {
        if ($macro instanceof Closure) {
            return call_user_func_array(Closure::bind($macro, null, get_called_class()), $parameters);
        }

        return call_user_func_array($macro, $parameters);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param string $method     magic method name called
     * @param array  $parameters parameters list
     *
     * @throws \BadMethodCallException
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        if (!static::hasMacro($method)) {
            foreach (static::getGenericMacros() as $callback) {
                try {
                    return static::executeStaticCallable($callback, $method, ...$parameters);
                } catch (BadMethodCallException $exception) {
                    continue;
                }
            }
            if (static::isStrictModeEnabled()) {
                throw new BadMethodCallException(sprintf(
                    'Method %s::%s does not exist.', static::class, $method
                ));
            }

            return null;
        }

        return static::executeStaticCallable(static::$globalMacros[$method], ...$parameters);
    }

    /**
     * Set specified unit to new given value.
     *
     * @param string $unit  year, month, day, hour, minute, second or microsecond
     * @param int    $value new value for given unit
     *
     * @return static
     */
    public function setUnit($unit, $value = null)
    {
        $unit = static::singularUnit($unit);
        $dateUnits = ['year', 'month', 'day'];
        if (in_array($unit, $dateUnits)) {
            return $this->setDate(...array_map(function ($name) use ($unit, $value) {
                return $name === $unit ? $value : $this->$name;
            }, $dateUnits));
        }

        $units = ['hour', 'minute', 'second', 'micro'];
        if ($unit === 'millisecond' || $unit === 'milli') {
            $value *= 1000;
            $unit = 'micro';
        } elseif ($unit === 'microsecond') {
            $unit = 'micro';
        }

        return $this->setTime(...array_map(function ($name) use ($unit, $value) {
            return $name === $unit ? $value : $this->$name;
        }, $units));
    }

    /**
     * Returns standardized singular of a given singular/plural unit name (in English).
     *
     * @param string $unit
     *
     * @return string
     */
    public static function singularUnit(string $unit): string
    {
        $unit = rtrim(strtolower($unit), 's');

        if ($unit === 'centurie') {
            return 'century';
        }

        if ($unit === 'millennia') {
            return 'millennium';
        }

        return $unit;
    }

    /**
     * Returns standardized plural of a given singular/plural unit name (in English).
     *
     * @param string $unit
     *
     * @return string
     */
    public static function pluralUnit(string $unit): string
    {
        $unit = rtrim(strtolower($unit), 's');

        if ($unit === 'century') {
            return 'centuries';
        }

        if ($unit === 'millennium' || $unit === 'millennia') {
            return 'millennia';
        }

        return "${unit}s";
    }

    protected function executeCallable($macro, ...$parameters)
    {
        if ($macro instanceof Closure) {
            return call_user_func_array($macro->bindTo($this, static::class), $parameters);
        }

        return call_user_func_array($macro, $parameters);
    }

    protected static function getGenericMacros()
    {
        foreach (static::$globalGenericMacros as $list) {
            foreach ($list as $macro) {
                yield $macro;
            }
        }
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param string $method     magic method name called
     * @param array  $parameters parameters list
     *
     * @throws \BadMethodCallException|\ReflectionException
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $diffSizes = [
            // @mode diffForHumans
            'short' => true,
            // @mode diffForHumans
            'long' => false,
        ];
        $diffSyntaxModes = [
            // @call diffForHumans
            'Absolute' => CarbonInterface::DIFF_ABSOLUTE,
            // @call diffForHumans
            'Relative' => CarbonInterface::DIFF_RELATIVE_AUTO,
            // @call diffForHumans
            'RelativeToNow' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
            // @call diffForHumans
            'RelativeToOther' => CarbonInterface::DIFF_RELATIVE_TO_OTHER,
        ];
        $sizePattern = implode('|', array_keys($diffSizes));
        $syntaxPattern = implode('|', array_keys($diffSyntaxModes));
        if (preg_match("/^(?<size>$sizePattern)(?<syntax>$syntaxPattern)DiffForHumans$/", $method, $match)) {
            $dates = array_filter($parameters, function ($parameter) {
                return $parameter instanceof DateTimeInterface;
            });
            $other = null;
            if (count($dates)) {
                $key = key($dates);
                $other = current($dates);
                array_splice($parameters, $key, 1);
            }

            return $this->diffForHumans($other, $diffSyntaxModes[$match['syntax']], $diffSizes[$match['size']], ...$parameters);
        }

        $action = substr($method, 0, 4);
        if ($action !== 'ceil') {
            $action = substr($method, 0, 5);
        }
        if (in_array($action, ['round', 'floor', 'ceil'])) {
            return $this->{$action.'Unit'}(substr($method, strlen($action)), ...$parameters);
        }

        $unit = rtrim($method, 's');
        if (substr($unit, 0, 2) === 'is') {
            $word = substr($unit, 2);
            if (in_array($word, static::$days)) {
                return $this->isDayOfWeek($word);
            }
            switch ($word) {
                // @call is Check if the current instance has UTC timezone. (Both isUtc and isUTC cases are valid.)
                case 'Utc':
                case 'UTC':
                    return $this->utc;
                // @call is Check if the current instance has non-UTC timezone.
                case 'Local':
                    return $this->local;
                // @call is Check if the current instance is a valid date.
                case 'Valid':
                    return $this->year !== 0;
                // @call is Check if the current instance is in a daylight saving time.
                case 'DST':
                    return $this->dst;
            }
        }

        $action = substr($unit, 0, 3);
        $overflow = null;
        if ($action === 'set') {
            $unit = strtolower(substr($unit, 3));
        }

        if (in_array($unit, static::$units)) {
            return $this->setUnit($unit, ...$parameters);
        }

        if ($action === 'add' || $action === 'sub') {
            $unit = substr($unit, 3);
            if (substr($unit, 0, 4) === 'Real') {
                $unit = static::singularUnit(substr($unit, 4));

                return $this->{"${action}RealUnit"}($unit, ...$parameters);
            }

            if (preg_match('/^(Month|Quarter|Year|Decade|Century|Centurie|Millennium|Millennia)s?(No|With|Without|WithNo)Overflow$/', $unit, $match)) {
                $unit = $match[1];
                $overflow = $match[2] === 'With';
            }
            $unit = static::singularUnit($unit);
        }

        if (static::isModifiableUnit($unit)) {
            return $this->{"${action}Unit"}($unit, $parameters[0] ?? 1, $overflow);
        }

        $sixFirstLetters = substr($unit, 0, 6);
        $factor = -1;

        if ($sixFirstLetters === 'isLast') {
            $sixFirstLetters = 'isNext';
            $factor = 1;
        }

        if ($sixFirstLetters === 'isNext') {
            $lowerUnit = strtolower(substr($unit, 6));

            if (static::isModifiableUnit($lowerUnit)) {
                return $this->copy()->addUnit($lowerUnit, $factor, false)->isSameUnit($lowerUnit, ...$parameters);
            }
        }

        if ($sixFirstLetters === 'isSame') {
            try {
                return $this->isSameUnit(strtolower(substr($unit, 6)), ...$parameters);
            } catch (InvalidArgumentException $exception) {
                // Try next
            }
        }

        if (substr($unit, 0, 9) === 'isCurrent') {
            try {
                return $this->isCurrentUnit(strtolower(substr($unit, 9)));
            } catch (InvalidArgumentException | BadMethodCallException $exception) {
                // Try macros
            }
        }

        if (!static::hasMacro($method)) {
            foreach ([$this->localGenericMacros ?: [], static::getGenericMacros()] as $list) {
                foreach ($list as $callback) {
                    try {
                        return $this->executeCallable($callback, $method, ...$parameters);
                    } catch (BadMethodCallException $exception) {
                        continue;
                    }
                }
            }
            if ($this->localStrictModeEnabled ?? static::isStrictModeEnabled()) {
                throw new BadMethodCallException("Method $method does not exist.");
            }

            return null;
        }

        return $this->executeCallable(($this->localMacros ?? [])[$method] ?? static::$globalMacros[$method], ...$parameters);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon\Traits;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Carbon\Translator;
use Closure;
use DateInterval;
use DateTimeInterface;

/**
 * Trait Difference.
 *
 * Depends on the following methods:
 *
 * @method bool lessThan($date)
 * @method DateInterval diff(\DateTimeInterface $date, bool $absolute = false)
 * @method CarbonInterface copy()
 * @method CarbonInterface resolveCarbon()
 * @method static Translator translator()
 */
trait Difference
{
    /**
     * @param DateInterval $diff
     * @param bool         $absolute
     *
     * @return CarbonInterval
     */
    protected static function fixDiffInterval(DateInterval $diff, $absolute)
    {
        $diff = CarbonInterval::instance($diff);
        // Work-around for https://bugs.php.net/bug.php?id=77145
        // @codeCoverageIgnoreStart
        if ($diff->f > 0 && $diff->y === -1 && $diff->m === 11 && $diff->d >= 27 && $diff->h === 23 && $diff->i === 59 && $diff->s === 59) {
            $diff->y = 0;
            $diff->m = 0;
            $diff->d = 0;
            $diff->h = 0;
            $diff->i = 0;
            $diff->s = 0;
            $diff->f = (1000000 - round($diff->f * 1000000)) / 1000000;
            $diff->invert();
        } elseif ($diff->f < 0) {
            if ($diff->s !== 0 || $diff->i !== 0 || $diff->h !== 0 || $diff->d !== 0 || $diff->m !== 0 || $diff->y !== 0) {
                $diff->f = (round($diff->f * 1000000) + 1000000) / 1000000;
                $diff->s--;

                if ($diff->s < 0) {
                    $diff->s += 60;
                    $diff->i--;

                    if ($diff->i < 0) {
                        $diff->i += 60;
                        $diff->h--;

                        if ($diff->h < 0) {
                            $diff->h += 24;
                            $diff->d--;

                            if ($diff->d < 0) {
                                $diff->d += 30;
                                $diff->m--;

                                if ($diff->m < 0) {
                                    $diff->m += 12;
                                    $diff->y--;
                                }
                            }
                        }
                    }
                }
            } else {
                $diff->f *= -1;
                $diff->invert();
            }
        }
        // @codeCoverageIgnoreEnd

        if ($absolute && $diff->invert) {
            $diff->invert();
        }

        return $diff;
    }

    /**
     * Get the difference as a CarbonInterval instance
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return CarbonInterval
     */
    public function diffAsCarbonInterval($date = null, $absolute = true)
    {
        return static::fixDiffInterval($this->diff($this->resolveCarbon($date), $absolute), $absolute);
    }

    /**
     * Get the difference in years
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInYears($date = null, $absolute = true)
    {
        return (int) $this->diff($this->resolveCarbon($date), $absolute)->format('%r%y');
    }

    /**
     * Get the difference in months
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInMonths($date = null, $absolute = true)
    {
        $date = $this->resolveCarbon($date);

        return $this->diffInYears($date, $absolute) * static::MONTHS_PER_YEAR + (int) $this->diff($date, $absolute)->format('%r%m');
    }

    /**
     * Get the difference in weeks
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInWeeks($date = null, $absolute = true)
    {
        return (int) ($this->diffInDays($date, $absolute) / static::DAYS_PER_WEEK);
    }

    /**
     * Get the difference in days
     *
     * @param Carbon|\DateTimeInterface|string|null $date
     * @param bool                                  $absolute Get the absolute of the difference
     *
     * @return int
     */
    public function diffInDays($date = null, $absolute = true)
    {
        return (int) $this->diff($this->resolveCarbon($date), $absolute)->format('%r%a');
    }

    /**
     * Get the difference in days using a filter closure
     *
     * @param Clos