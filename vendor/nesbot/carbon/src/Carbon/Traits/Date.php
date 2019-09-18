of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * first day of the current month.  Use the supplied constants
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek
     *
     * @return static|CarbonInterface
     */
    public function firstOfMonth($dayOfWeek = null)
    {
        $date = $this->startOfDay();

        if ($dayOfWeek === null) {
            return $date->day(1);
        }

        return $date->modify('first '.static::$days[$dayOfWeek].' of '.$date->rawFormat('F').' '.$date->year);
    }

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
    public function lastOfMonth($dayOfWeek = null)
    {
        $date = $this->startOfDay();

        if ($dayOfWeek === null) {
            return $date->day($date->daysInMonth);
        }

        return $date->modify('last '.static::$days[$dayOfWeek].' of '.$date->rawFormat('F').' '.$date->year);
    }

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
    public function nthOfMonth($nth, $dayOfWeek)
    {
        $date = $this->copy()->firstOfMonth();
        $check = $date->rawFormat('Y-m');
        $date = $date->modify('+'.$nth.' '.static::$days[$dayOfWeek]);

        return $date->rawFormat('Y-m') === $check ? $this->modify($date) : false;
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * first day of the current quarter.  Use the supplied constants
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek day of the week default null
     *
     * @return static|CarbonInterface
     */
    public function firstOfQuarter($dayOfWeek = null)
    {
        return $this->setDate($this->year, $this->quarter * static::MONTHS_PER_QUARTER - 2, 1)->firstOfMonth($dayOfWeek);
    }

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
    public function lastOfQuarter($dayOfWeek = null)
    {
        return $this->setDate($this->year, $this->quarter * static::MONTHS_PER_QUARTER, 1)->lastOfMonth($dayOfWeek);
    }

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
    public function nthOfQuarter($nth, $dayOfWeek)
    {
        $date = $this->copy()->day(1)->month($this->quarter * static::MONTHS_PER_QUARTER);
        $lastMonth = $date->month;
        $year = $date->year;
        $date = $date->firstOfQuarter()->modify('+'.$nth.' '.static::$days[$dayOfWeek]);

        return ($lastMonth < $date->month || $year !== $date->year) ? false : $this->modify($date);
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * first day of the current year.  Use the supplied constants
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek day of the week default null
     *
     * @return static|CarbonInterface
     */
    public function firstOfYear($dayOfWeek = null)
    {
        return $this->month(1)->firstOfMonth($dayOfWeek);
    }

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
    public function lastOfYear($dayOfWeek = null)
    {
        return $this->month(static::MONTHS_PER_YEAR)->lastOfMonth($dayOfWeek);
    }

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
    public function nthOfYear($nth, $dayOfWeek)
    {
        $date = $this->copy()->firstOfYear()->modify('+'.$nth.' '.static::$days[$dayOfWeek]);

        return $this->year === $date->year ? $this->modify($date) : false;
    }

    /**
     * Modify the current instance to the average of a given instance (default now) and the current instance
     * (second-precision).
     *
     * @param \Carbon\Carbon|\DateTimeInterface|null $date
     *
     * @return static|CarbonInterface
     */
    public function average($date = null)
    {
        return $this->addRealMicroseconds((int) ($this->diffInRealMicroseconds($this->resolveCarbon($date), false) / 2));
    }

    /**
     * Get the closest date from the instance (second-precision).
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date1
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date2
     *
     * @return static|CarbonInterface
     */
    public function closest($date1, $date2)
    {
        return $this->diffInRealMicroseconds($date1) < $this->diffInRealMicroseconds($date2) ? $date1 : $date2;
    }

    /**
     * Get the farthest date from the instance (second-precision).
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date1
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date2
     *
     * @return static|CarbonInterface
     */
    public function farthest($date1, $date2)
    {
        return $this->diffInRealMicroseconds($date1) > $this->diffInRealMicroseconds($date2) ? $date1 : $date2;
    }

    /**
     * Get the minimum instance between a given instance (default now) and the current instance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @return static|CarbonInterface
     */
    public function min($date = null)
    {
        $date = $this->resolveCarbon($date);

        return $this->lt($date) ? $this : $date;
    }

    /**
     * Get the minimum instance between a given instance (default now) and the current instance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @see min()
     *
     * @return static|CarbonInterface
     */
    public function minimum($date = null)
    {
        return $this->min($date);
    }

    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @return static|CarbonInterface
     */
    public function max($date = null)
    {
        $date = $this->resolveCarbon($date);

        return $this->gt($date) ? $this : $date;
    }

    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @param \Carbon\Carbon|\DateTimeInterface|mixed $date
     *
     * @see max()
     *
     * @return static|CarbonInterface
     */
    public function maximum($date = null)
    {
        return $this->max($date);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <?php

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

/**
 * Trait Mutability.
 *
 * Utils to know if the current object is mutable or immutable and convert it.
 */
trait Mutability
{
    /**
     * Returns true if the current class/instance is mutable.
     *
     * @return bool
     */
    public static function isMutable()
    {
        return false;
    }

    /**
     * Returns true if the current class/instance is immutable.
     *
     * @return bool
     */
    public static function isImmutable()
    {
        return !static::isMutable();
    }

    /**
     * Cast the current instance into the given class.
     *
     * @param string $className The $className::instance() method will be called to cast the current object.
     *
     * @return object
     */
    public function cast(string $className)
    {
        if (!method_exists($className, 'instance')) {
            throw new \InvalidArgumentException("$className has not the instance() method needed to cast the date.");
        }

        return $className::instance($this);
    }

    /**
     * Return a mutable copy of the instance.
     *
     * @return Carbon
     */
    public function toMutable()
    {
        /** @var Carbon $date */
        $date = $this->cast(Carbon::class);

        return $date;
    }

    /**
     * Return a immutable copy of the instance.
     *
     * @return CarbonImmutable
     */
    public function toImmutable()
    {
        /** @var CarbonImmutable $date */
        $date = $this->cast(CarbonImmutable::class);

        return $date;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon\Traits;

use Carbon\CarbonInterface;

/**
 * Trait Options.
 *
 * Embed base methods to change settings of Carbon classes.
 *
 * Depends on the following methods:
 *
 * @method \Carbon\Carbon|\Carbon\CarbonImmutable shiftTimezone($timezone) Set the timezone
 */
trait Options
{
    use Localization;

    /**
     * Customizable PHP_INT_SIZE override.
     *
     * @var int
     */
    public static $PHPIntSize = PHP_INT_SIZE;

    /**
     * First day of week.
     *
     * @var int
     */
    protected static $weekStartsAt = CarbonInterface::MONDAY;

    /**
     * Last day of week.
     *
     * @var int
     */
    protected static $weekEndsAt = CarbonInterface::SUNDAY;

    /**
     * Days of weekend.
     *
     * @var array
     */
    protected static $weekendDays = [
        CarbonInterface::SATURDAY,
        CarbonInterface::SUNDAY,
    ];

    /**
     * Format regex patterns.
     *
     * @var array
     */
    protected static $regexFormats = [
        'd' => '(3[01]|[12][0-9]|0[1-9])',
        'D' => '([a-zA-Z]{3})',
        'j' => '([123][0-9]|[1-9])',
        'l' => '([a-zA-Z]{2,})',
        'N' => '([1-7])',
        'S' => '([a-zA-Z]{2})',
        'w' => '([0-6])',
        'z' => '(36[0-5]|3[0-5][0-9]|[12][0-9]{2}|[1-9]?[0-9])',
        'W' => '(5[012]|[1-4][0-9]|[1-9])',
        'F' => '([a-zA-Z]{2,})',
        'm' => '(1[012]|0[1-9])',
        'M' => '([a-zA-Z]{3})',
        'n' => '(1[012]|[1-9])',
        't' => '(2[89]|3[01])',
        'L' => '(0|1)',
        'o' => '([1-9][0-9]{0,4})',
        'Y' => '([1-9]?[0-9]{4})',
        'y' => '([0-9]{2})',
        'a' => '(am|pm)',
        'A' => '(AM|PM)',
        'B' => '([0-9]{3})',
        'g' => '(1[012]|[1-9])',
        'G' => '(2[0-3]|1?[0-9])',
        'h' => '(1[012]|0[1-9])',
        'H' => '(2[0-3]|[01][0-9])',
        'i' => '([0-5][0-9])',
        's' => '([0-5][0-9])',
        'u' => '([0-9]{1,6})',
        'v' => '([0-9]{1,3})',
        'e' => '([a-zA-Z]{1,5})|([a-zA-Z]*\/[a-zA-Z]*)',
        'I' => '(0|1)',
        'O' => '([\+\-](1[012]|0[0-9])[0134][05])',
        'P' => '([\+\-](1[012]|0[0-9]):[0134][05])',
        'T' => '([a-zA-Z]{1,5})',
        'Z' => '(-?[1-5]?[0-9]{1,4})',
        'U' => '([0-9]*)',

        // The formats below are combinations of the above formats.
        'c' => '(([1-9]?[0-9]{4})\-(1[012]|0[1-9])\-(3[01]|[12][0-9]|0[1-9])T(2[0-3]|[01][0-9]):([0-5][0-9]):([0-5][0-9])[\+\-](1[012]|0[0-9]):([0134][05]))', // Y-m-dTH:i:sP
        'r' => '(([a-zA-Z]{3}), ([123][0-9]|[1-9]) ([a-zA-Z]{3}) ([1-9]?[0-9]{4}) (2[0-3]|[01][0-9]):([0-5][0-9]):([0-5][0-9]) [\+\-](1[012]|0[0-9])([0134][05]))', // D, j M Y H:i:s O
    ];

    /**
     * Indicates if months should be calculated with overflow.
     * Global setting.
     *
     * @var bool
     */
    protected static $monthsOverflow = true;

    /**
     * Indicates if years should be calculated with overflow.
     * Global setting.
     *
     * @var bool
     */
    protected static $yearsOverflow = true;

    /**
     * Indicates if the strict mode is in use.
     * Global setting.
     *
     * @var bool
     */
    protected static $strictModeEnabled = true;

    /**
     * Function to call instead of format.
     *
     * @var string|callable|null
     */
    protected static $formatFunction = null;

    /**
     * Function to call instead of createFromFormat.
     *
     * @var string|callable|null
     */
    protected static $createFromFormatFunction = null;

    /**
     * Function to call instead of parse.
     *
     * @var string|callable|null
     */
    protected static $parseFunction = null;

    /**
     * Indicates if months should be calculated with overflow.
     * Specific setting.
     *
     * @var bool|null
     */
    protected $localMonthsOverflow = null;

    /**
     * Indicates if years should be calculated with overflow.
     * Specific setting.
     *
     * @var bool|null
     */
    protected $localYearsOverflow = null;

    /**
     * Indicates if the strict mode is in use.
     * Specific setting.
     *
     * @var bool|null
     */
    protected $localStrictModeEnabled = null;

    /**
     * Options for diffForHumans and forHumans methods.
     *
     * @var bool|null
     */
    protected $localHumanDiffOptions = null;

    /**
     * Format to use on string cast.
     *
     * @var string|null
     */
    protected $localToStringFormat = null;

    /**
     * Format to use on JSON serialization.
     *
     * @var string|null
     */
    protected $localSerializer = null;

    /**
     * Instance-specific macros.
     *
     * @var array|null
     */
    protected $localMacros = null;

    /**
     * Instance-specific generic macros.
     *
     * @var array|null
     */
    protected $localGenericMacros = null;

    /**
     * Function to call instead of format.
     *
     * @var string|callable|null
     */
    protected $localFormatFunction = null;

    /**
     * @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
     *             You should rather use the ->settings() method.
     * @see settings
     *
     * Enable the strict mode (or disable with passing false).
     *
     * @param bool $strictModeEnabled
     */
    public static function useStrictMode($strictModeEnabled = true)
    {
        static::$strictModeEnabled = $strictModeEnabled;
    }

    /**
     * Returns true if the strict mode is globally in use, false else.
     * (It can be overridden in specific instances.)
     *
     * @return bool
     */
    public static function isStrictModeEnabled()
    {
        return static::$strictModeEnabled;
    }

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
    public static function useMonthsOverflow($monthsOverflow = true)
    {
        static::$monthsOverflow = $monthsOverflow;
    }

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
    public static function resetMonthsOverflow()
    {
        static::$monthsOverflow = true;
    }

    /**
     * Get the month overflow global behavior (can be overridden in specific instances).
     *
     * @return bool
     */
    public static function shouldOverflowMonths()
    {
        return static::$monthsOverflow;
    }

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
    public static function useYearsOverflow($yearsOverflow = true)
    {
        static::$yearsOverflow = $yearsOverflow;
    }

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
    public static function resetYearsOverflow()
    {
        static::$yearsOverflow = true;
    }

    /**
     * Get the month overflow global behavior (can be overridden in specific instances).
     *
     * @return bool
     */
    public static function shouldOverflowYears()
    {
        return static::$yearsOverflow;
    }

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
    public function settings(array $settings)
    {
        $this->localStrictModeEnabled = $settings['strictMode'] ?? null;
        $this->localMonthsOverflow = $settings['monthOverflow'] ?? null;
        $this->localYearsOverflow = $settings['yearOverflow'] ?? null;
        $this->localHumanDiffOptions = $settings['humanDiffOptions'] ?? null;
        $this->localToStringFormat = $settings['toStringFormat'] ?? null;
        $this->localSerializer = $settings['toJsonFormat'] ?? null;
        $this->localMacros = $settings['macros'] ?? null;
        $this->localGenericMacros = $settings['genericMacros'] ?? null;
        $this->localFormatFunction = $settings['formatFunction'] ?? null;
        $date = $this;
        if (isset($settings['locale'])) {
            $locales = $settings['locale'];

            if (!is_array($locales)) {
                $locales = [$locales];
            }

            $date = $date->locale(...$locales);
        }
        if (isset($settings['timezone'])) {
            $date = $date->shiftTimezone($settings['timezone']);
        }

        return $date;
    }

    /**
     * Returns current local settings.
     *
     * @return array
     */
    public function getSettings()
    {
        $settings = [];
        $map = [
            'localStrictModeEnabled' => 'strictMode',
            'localMonthsOverflow' => 'monthOverflow',
            'localYearsOverflow' => 'yearOverflow',
            'localHumanDiffOptions' => 'humanDiffOptions',
            'localToStringFormat' => 'toStringFormat',
            'localSerializer' => 'toJsonFormat',
            'localMacros' => 'macros',
            'localGenericMacros' => 'genericMacros',
            'locale' => 'locale',
            'tzName' => 'timezone',
            'localFormatFunction' => 'formatFunction',
        ];
        foreach ($map as $property => $key) {
            $value = $this->$property ?? null;
            if ($value !== null) {
                $settings[$key] = $value;
            }
        }

        return $settings;
    }

    /**
     * Show truthy properties on var_dump().
     *
     * @return array
     */
    public function __debugInfo()
    {
        return array_filter(get_object_vars($this), function ($var) {
            return $var;
        });
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon\Traits;

use Carbon\CarbonInterface;
use InvalidArgumentException;

/**
 * Trait Rounding.
 *
 * Round, ceil, floor units.
 *
 * Depends on the following methods:
 *
 * @method CarbonInterface copy()
 * @method CarbonInterface startOfWeek()
 */
trait Rounding
{
    /**
     * Round the current instance at the given unit with given precision if specified and the given function.
     *
     * @param string    $unit
     * @param float|int $precision
     * @param string    $function
     *
     * @return CarbonInterface
     */
    public function roundUnit($unit, $precision = 1, $function = 'round')
    {
        $metaUnits = [
            // @call roundUnit
            'millennium' => [static::YEARS_PER_MILLENNIUM, 'year'],
            // @call roundUnit
            'century' => [static::YEARS_PER_CENTURY, 'year'],
            // @call roundUnit
            'decade' => [static::YEARS_PER_DECADE, 'year'],
            // @call roundUnit
            'quarter' => [static::MONTHS_PER_QUARTER, 'month'],
            // @call roundUnit
            'millisecond' => [1000, 'microsecond'],
        ];
        $normalizedUnit = static::singularUnit($unit);
        $ranges = array_merge(static::getRangesByUnit(), [
            // @call roundUnit
            'microsecond' => [0, 999999],
        ]);
        $factor = 1;
        if (isset($metaUnits[$normalizedUnit])) {
            [$factor, $normalizedUnit] = $metaUnits[$normalizedUnit];
        }
        $precision *= $factor;

        if (!isset($ranges[$normalizedUnit])) {
            throw new InvalidArgumentException("Unknown unit '$unit' to floor");
        }

        $found = false;
        $fraction = 0;
        $arguments = null;
        $factor = $this->year < 0 ? -1 : 1;
        $changes = [];

        foreach ($ranges as $unit => [$minimum, $maximum]) {
            if ($normalizedUnit === $unit) {
                $arguments = [$this->$unit, $minimum];
                $fraction = $precision - floor($precision);
                $found = true;

                continue;
            }

            if ($found) {
                $delta = $maximum + 1 - $minimum;
                $factor /= $delta;
                $fraction *= $delta;
                $arguments[0] += $this->$unit * $factor;
                $changes[$unit] = round($minimum + ($fraction ? $fraction * call_user_func($function, ($this->$unit - $minimum) / $fraction) : 0));
                // Cannot use modulo as it lose double precision
                while ($changes[$unit] >= $delta) {
                    $changes[$unit] -= $delta;
                }
                $fraction -= floor($fraction);
            }
        }

        [$value, $minimum] = $arguments;
        /** @var CarbonInterface $result */
        $result = $this->$normalizedUnit(floor(call_user_func($function, ($value - $minimum) / $precision) * $precision + $minimum));
        foreach ($changes as $unit => $value) {
            $result = $result->$unit($value);
        }

        return $result;
    }

    /**
     * Truncate the current instance at the given unit with given precision if specified.
     *
     * @param string    $unit
     * @param float|int $precision
     *
     * @return CarbonInterface
     */
    public function floorUnit($unit, $precision = 1)
    {
        return $this->roundUnit($unit, $precision, 'floor');
    }

    /**
     * Ceil the current instance at the given unit with given precision if specified.
     *
     * @param string    $unit
     * @param float|int $precision
     *
     * @return CarbonInterface
     */
    public function ceilUnit($unit, $precision = 1)
    {
        return $this->roundUnit($unit, $precision, 'ceil');
    }

    /**
     * Round the current instance second with given precision if specified.
     *
     * @param float|int $precision
     * @param string    $function
     *
     * @return CarbonInterface
     */
    public function round($precision = 1, $function = 'round')
    {
        return $this->roundUnit('second', $precision, $function);
    }

    /**
     * Round the current instance second with given precision if specified.
     *
     * @param float|int $precision
     *
     * @return CarbonInterface
     */
    public function floor($precision = 1)
    {
        return $this->roundUnit('second', $precision, 'floor');
    }

    /**
     * Ceil the current instance second with given precision if specified.
     *
     * @param float|int $precision
     *
     * @return CarbonInterface
     */
    public function ceil($precision = 1)
    {
        return $this->roundUnit('second', $precision, 'ceil');
    }

    /**
     * Round the current instance week.
     *
     * @param int $weekStartsAt optional start allow you to specify the day of week to use to start the week
     *
     * @return CarbonInterface
     */
    public function roundWeek($weekStartsAt = null)
    {
        return $this->closest($this->copy()->floorWeek($weekStartsAt), $this->copy()->ceilWeek($weekStartsAt));
    }

    /**
     * Truncate the current instance week.
     *
     * @param int $weekStartsAt optional start allow you to specify the day of week to use to start the week
     *
     * @return CarbonInterface
     */
    public function floorWeek($weekStartsAt = null)
    {
        return $this->startOfWeek($weekStartsAt);
    }

    /**
     * Ceil the current instance week.
     *
     * @param int $weekStartsAt optional start allow you to specify the day of week to use to start the week
     *
     * @return CarbonInterface
     */
    public function ceilWeek($weekStartsAt = null)
    {
        if ($this->isMutable()) {
            $startOfWeek = $this->copy()->startOfWeek($weekStartsAt);

            return $startOfWeek != $this ?
                $this->startOfWeek($weekStartsAt)->addWeek() :
                $this;
        }

        $startOfWeek = $this->startOfWeek($weekStartsAt);

        return $startOfWeek != $this ?
            $startOfWeek->addWeek() :
            $this->copy();
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon\Traits;

use Carbon\CarbonInterface;
use InvalidArgumentException;

/**
 * Trait Serialization.
 *
 * Serialization and JSON stuff.
 *
 * Depends on the following properties:
 *
 * @property int $year
 * @property int $month
 * @property int $daysInMonth
 * @property int $quarter
 *
 * Depends on the following methods:
 *
 * @method string|static locale(string $locale = null)
 * @method string        toJSON()
 */
trait Serialization
{
    /**
     * The custom Carbon JSON serializer.
     *
     * @var callable|null
     */
    protected static $serializer;

    /**
     * Locale to dump comes here before serialization.
     *
     * @var string|null
     */
    protected $dumpLocale = null;

    /**
     * Return a serialized string of the instance.
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this);
    }

    /**
     * Create an instance from a serialized string.
     *
     * @param string $value
     *
     * @throws \InvalidArgumentException
     *
     * @return static|CarbonInterface
     */
    public static function fromSerialized($value)
    {
        $instance = @unserialize($value);

        if (!$instance instanceof static) {
            throw new InvalidArgumentException('Invalid serialized value.');
        }

        return $instance;
    }

    /**
     * The __set_state handler.
     *
     * @param string|array $dump
     *
     * @return static|CarbonInterface
     */
    public static function __set_state($dump)
    {
        if (is_string($dump)) {
            return static::parse($dump);
        }

        /** @var \DateTimeInterface $date */
        $date = get_parent_class(static::class) && method_exists(parent::class, '__set_state')
            ? parent::__set_state($dump)
            : (object) $dump;

        return static::instance($date);
    }

    /**
     * Returns the list of properties to dump on serialize() called on.
     *
     * @return array
     */
    public function __sleep()
    {
        $properties = ['date', 'timezone_type', 'timezone'];
        if ($this->localTranslator ?? null) {
            $properties[] = 'dumpLocale';
            $this->dumpLocale = $this->locale ?? null;
        }

        return $properties;
    }

    /**
     * Set locale if specified on unserialize() called.
     */
    public function __wakeup()
    {
        if (get_parent_class() && method_exists(parent::class, '__wakeup')) {
            parent::__wakeup();
        }
        if (isset($this->dumpLocale)) {
            $this->locale($this->dumpLocale);
            $this->dumpLocale = null;
        }
    }

    /**
     * Prepare the object for JSON serialization.
     *
     * @return array|string
     */
    public function jsonSerialize()
    {
        $serializer = $this->localSerializer ?? static::$serializer;
        if ($serializer) {
            return is_string($serializer)
                ? $this->rawFormat($serializer)
                : call_user_func($serializer, $this);
        }

        return $this->toJSON();
    }

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
    public static function serializeUsing($callback)
    {
        static::$serializer = $callback;
    }
}
                                                                                                                                                                                                                                                                                                                       <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon\Traits;

use Carbon\CarbonInterface;

trait Test
{
    ///////////////////////////////////////////////////////////////////
    ///////////////////////// TESTING AIDS ////////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * A test Carbon instance to be returned when now instances are created.
     *
     * @var static|CarbonInterface
     */
    protected static $testNow;

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
    public static function setTestNow($testNow = null)
    {
        static::$testNow = is_string($testNow) ? static::parse($testNow) : $testNow;
    }

    /**
     * Get the Carbon instance (real or mock) to be returned when a "now"
     * instance is created.
     *
     * @return static|CarbonInterface the current instance used for testing
     */
    public static function getTestNow()
    {
        return static::$testNow;
    }

    /**
     * Determine if there is a valid test instance set. A valid test instance
     * is anything that is not null.
     *
     * @return bool true if there is a test instance, otherwise false
     */
    public static function hasTestNow()
    {
        return static::getTestNow() !== null;
    }

    protected static function mockConstructorParameters(&$time, &$tz)
    {
        /** @var \Carbon\CarbonImmutable|\Carbon\Carbon $testInstance */
        $testInstance = clone static::getTestNow();

        //shift the time according to the given time zone
        if ($tz !== null && $tz !== static::getTestNow()->getTimezone()) {
            $testInstance = $testInstance->setTimezone($tz);
        } else {
            $tz = $testInstance->getTimezone();
        }

        if (static::hasRelativeKeywords($time)) {
            $testInstance = $testInstance->modify($time);
        }

        $time = $testInstance instanceof self ? $testInstance->rawFormat(static::MOCK_DATETIME_FORMAT) : $testInstance->format(static::MOCK_DATETIME_FORMAT);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon\Traits;

use Carbon\CarbonInterface;

/**
 * Trait Timestamp.
 */
trait Timestamp
{
    /**
     * Create a Carbon instance from a timestamp.
     *
     * @param int                       $timestamp
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function createFromTimestamp($timestamp, $tz = null)
    {
        return static::today($tz)->setTimestamp($timestamp);
    }

    /**
     * Create a Carbon instance from a timestamp in milliseconds.
     *
     * @param int                       $timestamp
     * @param \DateTimeZone|string|null $tz
     *
     * @return static|CarbonInterface
     */
    public static function createFromTimestampMs($timestamp, $tz = null)
    {
        return static::rawCreateFromFormat('U.u', sprintf('%F', $timestamp / 1000))
            ->setTimezone($tz);
    }

    /**
     * Create a Carbon instance from an UTC timestamp.
     *
     * @param int $timestamp
     *
     * @return static|CarbonInterface
     */
    public static function createFromTimestampUTC($timestamp)
    {
        return new static('@'.$timestamp);
    }

    /**
     * Set the instance's timestamp.
     *
     * @param int $value
     *
     * @return static|CarbonInterface
     */
    public function timestamp($value)
    {
        return $this->setTimestamp($value);
    }

    /**
     * Returns a timestamp rounded with the given precision (6 by default).
     *
     * @example getPreciseTimestamp()   1532087464437474 (microsecond maximum precision)
     * @example getPreciseTimestamp(6)  1532087464437474
     * @example getPreciseTimestamp(5)  153208746443747  (1/100000 second precision)
     * @example getPreciseTimestamp(4)  15320874644375   (1/10000 second precision)
     * @example getPreciseTimestamp(3)  1532087464437    (millisecond precision)
     * @example getPreciseTimestamp(2)  153208746444     (1/100 second precision)
     * @example getPreciseTimestamp(1)  15320874644      (1/10 second precision)
     * @example getPreciseTimestamp(0)  1532087464       (second precision)
     * @example getPreciseTimestamp(-1) 153208746        (10 second precision)
     * @example getPreciseTimestamp(-2) 15320875         (100 second precision)
     *
     * @param int $precision
     *
     * @return float
     */
    public function getPreciseTimestamp($precision = 6)
    {
        return round($this->rawFormat('Uu') / pow(10, 6 - $precision));
    }

    /**
     * Returns the milliseconds timestamps used amongst other by Date javascript objects.
     *
     * @return float
     */
    public function valueOf()
    {
        return $this->getPreciseTimestamp(3);
    }

    /**
     * @alias getTimestamp
     *
     * Returns the UNIX timestamp for the current date.
     *
     * @return int
     */
    public function unix()
    {
        return $this->getTimestamp();
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon\Traits;

use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use DateInterval;
use InvalidArgumentException;

/**
 * Trait Units.
 *
 * Add, subtract and set units.
 */
trait Units
{
    /**
     * Add seconds to the instance using timestamp. Positive $value travels
     * forward while negative $value travels into the past.
     *
     * @param string $unit
     * @param int    $value
     *
     * @return static
     */
    public function addRealUnit($unit, $value = 1)
    {
        switch ($unit) {
            // @call addRealUnit
            case 'micro':
            // @call addRealUnit
            case 'microsecond':
                /* @var CarbonInterface $this */
                $diff = $this->microsecond + $value;
                $time = $this->getTimestamp();
                $seconds = (int) floor($diff / static::MICROSECONDS_PER_SECOND);
                $time += $seconds;
                $diff -= $seconds * static::MICROSECONDS_PER_SECOND;
                $microtime = str_pad($diff, 6, '0', STR_PAD_LEFT);
                $tz = $this->tz;

                return $this->tz('UTC')->modify("@$time.$microtime")->tz($tz);
            // @call addRealUnit
            case 'milli':
            // @call addRealUnit
            case 'millisecond':
                return $this->addRealUnit('microsecond', $value * static::MICROSECONDS_PER_MILLISECOND);
                break;
            // @call addRealUnit
            case 'second':
                break;
            // @call addRealUnit
            case 'minute':
                $value *= static::SECONDS_PER_MINUTE;
                break;
            // @call addRealUnit
            case 'hour':
                $value *= static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            // @call addRealUnit
            case 'day':
                $value *= static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            // @call addRealUnit
            case 'week':
                $value *= static::DAYS_PER_WEEK * static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            // @call addRealUnit
            case 'month':
                $value *= 30 * static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            // @call addRealUnit
            case 'quarter':
                $value *= static::MONTHS_PER_QUARTER * 30 * static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            // @call addRealUnit
            case 'year':
                $value *= 365 * static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            // @call addRealUnit
            case 'decade':
                $value *= static::YEARS_PER_DECADE * 365 * static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            // @call addRealUnit
            case 'century':
                $value *= static::YEARS_PER_CENTURY * 365 * static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            // @call addRealUnit
            case 'millennium':
                $value *= static::YEARS_PER_MILLENNIUM * 365 * static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            default:
                if ($this->localStrictModeEnabled ?? static::isStrictModeEnabled()) {
                    throw new InvalidArgumentException("Invalid unit for real timestamp add/sub: '$unit'");
                }

                return $this;
        }

        /* @var CarbonInterface $this */
        return $this->setTimestamp($this->getTimestamp() + $value);
    }

    public function subRealUnit($unit, $value = 1)
    {
        return $this->addRealUnit($unit, -$value);
    }

    /**
     * Returns true if a property can be changed via setter.
     *
     * @param string $unit
     *
     * @return bool
     */
    public static function isModifiableUnit($unit)
    {
        static $modifiableUnits = [
            // @call addUnit
            'millennium',
            // @call addUnit
            'century',
            // @call addUnit
            'decade',
            // @call addUnit
            'quarter',
            // @call addUnit
            'week',
            // @call addUnit
            'weekday',
        ];

        return in_array($unit, $modifiableUnits) || in_array($unit, static::$units);
    }

    /**
     * Add given units or interval to the current instance.
     *
     * @example $date->add('hour', 3)
     * @example $date->add(15, 'days')
     * @example $date->add(CarbonInterval::days(4))
     *
     * @param string|DateInterval $unit
     * @param int                 $value
     * @param bool|null           $overflow
     *
     * @return CarbonInterface
     */
    public function add($unit, $value = 1, $overflow = null)
    {
        if (is_string($unit) && func_num_args() === 1) {
            $unit = CarbonInterval::make($unit);
        }

        if ($unit instanceof DateInterval) {
            return parent::add($unit);
        }

        if (is_numeric($unit)) {
            $tempUnit = $value;
            $value = $unit;
            $unit = $tempUnit;
        }

        return $this->addUnit($unit, $value, $overflow);
    }

    /**
     * Add given units to the current instance.
     *
     * @param string    $unit
     * @param int       $value
     * @param bool|null $overflow
     *
     * @return CarbonInterface
     */
    public function addUnit($unit, $value = 1, $overflow = null)
    {
        /** @var CarbonInterface $date */
        $date = $this;

        if (!is_numeric($value) || !floatval($value)) {
            return $date->isMutable() ? $date : $date->copy();
        }

        $metaUnits = [
            'millennium' => [static::YEARS_PER_MILLENNIUM, 'year'],
            'century' => [static::YEARS_PER_CENTURY, 'year'],
            'decade' => [static::YEARS_PER_DECADE, 'year'],
            'quarter' => [static::MONTHS_PER_QUARTER, 'month'],
        ];
        if (isset($metaUnits[$unit])) {
            [$factor, $unit] = $metaUnits[$unit];
            $value *= $factor;
        }

        if ($unit === 'weekday') {
            $weekendDays = static::getWeekendDays();
            if ($weekendDays !== [static::SATURDAY, static::SUNDAY]) {
                $absoluteValue = abs($value);
                $sign = $value / max(1, $absoluteValue);
                $weekDaysCount = 7 - min(6, count(array_unique($weekendDays)));
                $weeks = floor($absoluteValue / $weekDaysCount);
                for ($diff = $absoluteValue % $weekDaysCount; $diff; $diff--) {
                    $date = $date->addDays($sign);
                    while (in_array($date->dayOfWeek, $weekendDays)) {
                        $date = $date->addDays($sign);
                    }
                }

                $value = $weeks * $sign;
                $unit = 'week';
            }

            $timeString = $date->toTimeString();
        } elseif ($canOverflow = in_array($unit, [
                'month',
                'year',
            ]) && ($overflow === false || (
                $overflow === null &&
                ($ucUnit = ucfirst($unit).'s') &&
                !($this->{'local'.$ucUnit.'Overflow'} ?? static::{'shouldOverflow'.$ucUnit}())
            ))) {
            $day = $date->day;
        }

        $value = (int) $value;

        if ($unit === 'milli' || $unit === 'millisecond') {
            $unit = 'microsecond';
            $value *= static::MICROSECONDS_PER_MILLISECOND;
        }

        // Work-around for bug https://bugs.php.net/bug.php?id=75642
        if ($unit === 'micro' || $unit === 'microsecond') {
            $microseconds = $this->micro + $value;
            $second = (int) floor($microseconds / static::MICROSECONDS_PER_SECOND);
            $microseconds %= static::MICROSECONDS_PER_SECOND;
            if ($microseconds < 0) {
                $microseconds += static::MICROSECONDS_PER_SECOND;
            }
            $date = $date->microseconds($microseconds);
            $unit = 'second';
            $value = $second;
        }
        $date = $date->modify("$value $unit");

        if (isset($timeString)) {
            return $date->setTimeFromTimeString($timeString);
        }

        if (isset($canOverflow, $day) && $canOverflow && $day !== $date->day) {
            $date = $date->modify('last day of previous month');
        }

        return $date;
    }

    /**
     * Subtract given units to the current instance.
     *
     * @param string    $unit
     * @param int       $value
     * @param bool|null $overflow
     *
     * @return CarbonInterface
     */
    public function subUnit($unit, $value = 1, $overflow = null)
    {
        return $this->addUnit($unit, -$value, $overflow);
    }

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
    public function sub($unit, $value = 1, $overflow = null)
    {
        if (is_string($unit) && func_num_args() === 1) {
            $unit = CarbonInterval::make($unit);
        }

        if ($unit instanceof DateInterval) {
            return parent::sub($unit);
        }

        if (is_numeric($unit)) {
            $_unit = $value;
            $value = $unit;
            $unit = $_unit;
            unset($_unit);
        }

        return $this->addUnit($unit, -floatval($value), $overflow);
    }

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
    public function subtract($unit, $value = 1, $overflow = null)
    {
        if (is_string($unit) && func_num_args() === 1) {
            $unit = CarbonInterval::make($unit);
        }

        return $this->sub($unit, $value, $overflow);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon\Traits;

use Carbon\CarbonInterface;

/**
 * Trait Week.
 *
 * week and ISO week number, year and count in year.
 *
 * Depends on the following properties:
 *
 * @property int $daysInYear
 * @property int $dayOfWeek
 * @property int $dayOfYear
 * @property int $year
 *
 * Depends on the following methods:
 *
 * @method CarbonInterface|static addWeeks(int $weeks = 1)
 * @method CarbonInterface|static copy()
 * @method CarbonInterface|static dayOfYear(int $dayOfYear)
 * @method string                 getTranslationMessage(string $key)
 * @method CarbonInterface|static next(int $day)
 * @method CarbonInterface|static startOfWeek(int $day = 1)
 * @method CarbonInterface|static subWeeks(int $weeks = 1)
 * @method CarbonInterface|static year(int $year = null)
 */
trait Week
{
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
    public function isoWeekYear($year = null, $dayOfWeek = null, $dayOfYear = null)
    {
        return $this->weekYear(
            $year,
            $dayOfWeek ?? 1,
            $dayOfYear ?? 4
        );
    }

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
    public function weekYear($year = null, $dayOfWeek = null, $dayOfYear = null)
    {
        $dayOfWeek = $dayOfWeek ?? $this->getTranslationMessage('first_day_of_week') ?? 0;
        $dayOfYear = $dayOfYear ?? $this->getTranslationMessage('day_of_first_week_of_year') ?? 1;

        if ($year !== null) {
            $year = (int) round($year);

            if ($this->weekYear(null, $dayOfWeek, $dayOfYear) === $year) {
                return $this->copy();
            }

            $week = $this->week(null, $dayOfWeek, $dayOfYear);
            $day = $this->dayOfWeek;
            $date = $this->year($year);
            switch ($date->weekYear(null, $dayOfWeek, $dayOfYear) - $year) {
                case 1:
                    $date = $date->subWeeks(26);
                    break;
                case -1:
                    $date = $date->addWeeks(26);
                    break;
            }

            $date = $date->addWeeks($week - $date->week(null, $dayOfWeek, $dayOfYear))->startOfWeek($dayOfWeek);

            if ($date->dayOfWeek === $day) {
                return $date;
            }

            return $date->next($day);
        }

        $year = $this->year;
        $day = $this->dayOfYear;
        $date = $this->copy()->dayOfYear($dayOfYear)->startOfWeek($dayOfWeek);

        if ($date->year === $year && $day < $date->dayOfYear) {
            return $year - 1;
        }

        $date = $this->copy()->addYear()->dayOfYear($dayOfYear)->startOfWeek($dayOfWeek);

        if ($date->year === $year && $day >= $date->dayOfYear) {
            return $year + 1;
        }

        return $year;
    }

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
    public function isoWeeksInYear($dayOfWeek = null, $dayOfYear = null)
    {
        return $this->weeksInYear(
            $dayOfWeek ?? 1,
            $dayOfYear ?? 4
        );
    }

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
    public function weeksInYear($dayOfWeek = null, $dayOfYear = null)
    {
        $dayOfWeek = $dayOfWeek ?? $this->getTranslationMessage('first_day_of_week') ?? 0;
        $dayOfYear = $dayOfYear ?? $this->getTranslationMessage('day_of_first_week_of_year') ?? 1;
        $year = $this->year;
        $start = $this->copy()->dayOfYear($dayOfYear)->startOfWeek($dayOfWeek);
        $startDay = $start->dayOfYear;
        if ($start->year !== $year) {
            $startDay -= $start->daysInYear;
        }
        $end = $this->copy()->addYear()->dayOfYear($dayOfYear)->startOfWeek($dayOfWeek);
        $endDay = $end->dayOfYear;
        if ($end->year !== $year) {
            $endDay += $this->daysInYear;
        }

        return (int) round(($endDay - $startDay) / 7);
    }

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
    public function week($week = null, $dayOfWeek = null, $dayOfYear = null)
    {
        $date = $this;
        $dayOfWeek = $dayOfWeek ?? $this->getTranslationMessage('first_day_of_week') ?? 0;
        $dayOfYear = $dayOfYear ?? $this->getTranslationMessage('day_of_first_week_of_year') ?? 1;

        if ($week !== null) {
            return $date->addWeeks(round($week) - $this->week(null, $dayOfWeek, $dayOfYear));
        }

        $start = $date->copy()->dayOfYear($dayOfYear)->startOfWeek($dayOfWeek);
        $end = $date->copy()->startOfWeek($dayOfWeek);
        if ($start > $end) {
            $start = $start->subWeeks(26)->dayOfYear($dayOfYear)->startOfWeek($dayOfWeek);
        }
        $week = (int) ($start->diffInDays($end) / 7 + 1);

        return $week > $end->weeksInYear($dayOfWeek, $dayOfYear) ? 1 : $week;
    }

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
    public function isoWeek($week = null, $dayOfWeek = null, $dayOfYear = null)
    {
        return $this->week(
            $week,
            $dayOfWeek ?? 1,
            $dayOfYear ?? 4
        );
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           Version 4.2.2-dev
-----------------

Nothing yet.

Version 4.2.1 (2019-02-16)
--------------------------

### Added

* [PHP 7.4] Add support for `??=` operator through a new `AssignOp\Coalesce` node. (#575)

Version 4.2.0 (2019-01-12)
--------------------------

### Added

* [PHP 7.4] Add support for typed properties through a new `type` subnode of `Stmt\Property`.
  Additionally `Builder\Property` now has a `setType()` method. (#567)
* Add `kind` attribute to `Cast\Double_`, which allows to distinguish between `(float)`,
  `(double)` and `(real)`. The form of the cast will be preserved by the pretty printer. (#565)

### Fixed

* Remove assertion when pretty printing anonymous class with a name (#554).

Version 4.1.1 (2018-12-26)
--------------------------

### Fixed

* Fix "undefined offset" notice when parsing specific malformed code (#551).

### Added

* Support error recovery for missing return type (`function foo() : {}`) (#544).

Version 4.1.0 (2018-10-10)
--------------------------

### Added

* Added support for PHP 7.3 flexible heredoc/nowdoc strings, completing support for PHP 7.3. There
  are two caveats for this feature:
   * In some rare, pathological cases flexible heredoc/nowdoc strings change the interpretation of
     existing doc strings. PHP-Parser will now use the new interpretation.
   * Flexible heredoc/nowdoc strings require special support from the lexer. Because this is not
     available on PHP versions before 7.3, support has to be emulated. This emulation is not perfect
     and some cases which we do not expect to occur in practice (such as flexible doc strings being
     nested within each other through abuse of variable-variable interpolation syntax) may not be
     recognized correctly.
* Added `DONT_TRAVERSE_CURRENT_AND_CHILDREN` to `NodeTraverser` to skip both traversal of child
  nodes, and prevent subsequent visitors from visiting the current node.

Version 4.0.4 (2018-09-18)
--------------------------

### Added

* The following methods have been added to `BuilderFactory`:
  * `useTrait()` (fluent builder)
  * `traitUseAdaptation()` (fluent builder)
  * `useFunction()` (fluent builder)
  * `useConst()` (fluent builder)
  * `var()`
  * `propertyFetch()`
  
### Deprecated

* `Builder\Param::setTypeHint()` has been deprecated in favor of the newly introduced
  `Builder\Param::setType()`.

Version 4.0.3 (2018-07-15)
--------------------------

### Fixed

* Fixed possible undefined offset notice in formatting-preserving printer. (#513)

### Added

* Improved error recovery inside arrays.
* Preserve trailing comment inside classes. **Note:** This change is possibly BC breaking if your
  code validates that classes can only contain certain statement types. After this change, classes
  can also contain Nop statements, while this was not previously possible. (#509)

Version 4.0.2 (2018-06-03)
--------------------------

### Added

* Improved error recovery inside classes.
* Support error recovery for `foreach` without `as`.
* Support error recovery for parameters without variable (`function (Type ) {}`).
* Support error recovery for functions without body (`function ($foo)`).

Version 4.0.1 (2018-03-25)
--------------------------

### Added

* [PHP 7.3] Added support for trailing commas in function calls.
* [PHP 7.3] Added support for by-reference array destructuring. 
* Added checks to node traverser to prevent replacing a statement with an expression or vice versa.
  This should prevent common mistakes in the implementation of node visitors.
* Added the following methods to `BuilderFactory`, to simplify creation of expressions:
  * `funcCall()`
  * `methodCall()`
  * `staticCall()`
  * `new()`
  * `constFetch()`
  * `classConstFetch()`

Version 4.0.0 (2018-02-28)
--------------------------

* No significant code changes since the beta 1 release.

Version 4.0.0-beta1 (2018-01-27)
--------------------------------

### Fixed

* In formatting-preserving pretty printer: Fixed indentation when inserting into lists. (#466)

### Added

* In formatting-preserving pretty printer: Improved formatting of elements inserted into multi-line
  arrays.

### Removed

* The `Autoloader` class has been removed. It is now required to use the Composer autoloader.

Version 4.0.0-alpha3 (2017-12-26)
---------------------------------

### Fixed

* In the formatting-preserving pretty printer:
  * Fixed comment indentation.
  * Fixed handling of inline HTML in the fallback case.
  * Fixed insertion into list nodes that require creation of a code block.

### Added

* Added support for inserting at the start of list nodes in formatting-preserving pretty printer.

Version 4.0.0-alpha2 (2017-11-10)
---------------------------------

### Added

* In the formatting-preserving pretty printer:
  * Added support for changing modifiers.
  * Added support for anonymous classes.
  * Added support for removing from list nodes.
  * Improved support for changing comments.
* Added start token offsets to comments.

Version 4.0.0-alpha1 (2017-10-18)
---------------------------------

### Added

* Added experimental support for format-preserving pretty-printing. In this mode formatting will be
  preserved for parts of the code which have not been modified.
* Added `replaceNodes` option to `NameResolver`, defaulting to true. If this option is disabled,
  resolved names will be added as `resolvedName` attributes, instead of replacing the original
  names.
* Added `NodeFinder` class, which can be used to find nodes based on a callback or class name. This
  is a utility to avoid custom node visitor implementations for simple search operations.
* Added `ClassMethod::isMagic()` method.
* Added `BuilderFactory` methods: `val()` method for creating an AST for a simple value, `concat()`
  for creating concatenation trees, `args()` for preparing function arguments.
* Added `NameContext` class, which encapsulates the `NameResolver` logic independently of the actual
  AST traversal. This facilitates use in other context, such as class names in doc comments.
  Additionally it provides an API for getting the shortest representation of a name.
* Added `Node::setAttributes()` method.
* Added `JsonDecoder`. This allows conversion JSON back into an AST.
* Added `Name` methods `toLowerString()` and `isSpecialClassName()`.
* Added `Identifier` and `VarLikeIdentifier` nodes, which are used in place of simple strings in
  many places.
* Added `getComments()`, `getStartLine()`, `getEndLine()`, `getStartTokenPos()`, `getEndTokenPos()`,
  `getStartFilePos()` and `getEndFilePos()` methods to `Node`. These provide a more obvious access
  point for the already existing attributes of the same name.
* Added `ConstExprEvaluator` to evaluate constant expressions to PHP values.
* Added `Expr\BinaryOp::getOperatorSigil()`, returning `+` for `Expr\BinaryOp\Plus`, etc.

### Changed

* Many subnodes that previously held simple strings now use `Identifier` (or `VarLikeIdentifier`)
  nodes. Please see the UPGRADE-4.0 file for an exhaustive list of affected nodes and some notes on
  possible impact.
* Expression statements (`expr;`) are now represented using a `Stmt\Expression` node. Previously
  these statements were directly represented as their constituent expression.
* The `name` subnode of `Param` has been renamed to `var` and now contains a `Variable` rather than
  a plain string.
* The `name` subnode of `StaticVar` has been renamed to `var` and now contains a `Variable` rather
  than a plain string.
* The `var` subnode of `ClosureUse` now contains a `Variable` rather than a plain string.
* The `var` subnode of `Catch` now contains a `Variable` rather than a plain string.
* The `alias` subnode of `UseUse` is now `null` if no explicit alias is given. As such,
  `use Foo\Bar` and `use Foo\Bar as Bar` are now represented differently. The `getAlias()` method
  can be used to get the effective alias, even if it is not explicitly given.

### Removed

* Support for running on PHP 5 and HHVM has been removed. You can however still parse code of old
  PHP versions (such as PHP 5.2), while running on PHP 7.
* Removed `type` subnode on `Class`, `ClassMethod` and `Property` nodes. Use `flags` instead.
* The `ClassConst::isStatic()` method has been removed. Constants cannot have a static modifier.
* The `NodeTraverser` no longer accepts `false` as a return value from a `leaveNode()` method.
  `NodeTraverser::REMOVE_NODE` should be returned instead.
* The `Node::setLine()` method has been removed. If you really need to, you can use `setAttribute()`
  instead.
* The misspelled `Class_::VISIBILITY_MODIFER_MASK` constant has been dropped in favor of
  `Class_::VISIBILITY_MODIFIER_MASK`.
* The XML serializer has been removed. As such, the classes `Serializer\XML`, and
  `Unserializer\XML`, as well as the interfaces `Serializer` and `Unserializer` no longer exist.
* The `BuilderAbstract` class has been removed. It's functionality is moved into `BuilderHelpers`.
  However, this is an internal class and should not be used directly.

Version 3.1.5 (2018-02-28)
--------------------------

### Fixed

* Fixed duplicate comment assignment in switch statements. (#469)
* Improve compatibility with PHP-Scoper. (#477)

Version 3.1.4 (2018-01-25)
--------------------------

### Fixed

* Fixed pretty printing of `-(-$x)` and `+(+$x)`. (#459)

Version 3.1.3 (2017-12-26)
--------------------------

### Fixed

* Improve compatibility with php-scoper, by supporting prefixed namespaces in
  `NodeAbstract::getType()`.

Version 3.1.2 (2017-11-04)
--------------------------

### Fixed

* Comments on empty blocks are now preserved on a `Stmt\Nop` node. (#382)

### Added

* Added `kind` attribute for `Stmt\Namespace_` node, which is one of `KIND_SEMICOLON` or
  `KIND_BRACED`. (#417)
* Added `setDocComment()` method to namespace builder. (#437)

Version 3.1.1 (2017-09-02)
--------------------------

### Fixed

* Fixed syntax error on comment after brace-style namespace declaration. (#412)
* Added support for TraitUse statements in trait builder. (#413)

Version 3.1.0 (2017-07-28)
--------------------------

### Added

* [PHP 7.2] Added support for trailing comma in group use statements.
* [PHP 7.2] Added support for `object` type. This means `object` types will now be represented as a
  builtin type (a simple `"object"` string), rather than a class `Name`.

### Fixed

* Floating-point numbers are now printed correctly if the LC_NUMERIC locale uses a comma as decimal
  separator.

### Changed

* `Name::$parts` is no longer deprecated.

Version 3.0.6 (2017-06-28)
--------------------------

### Fixed

* Fixed the spelling of `Class_::VISIBILITY_MODIFIER_MASK`. The previous spelling of
  `Class_::VISIBILITY_MODIFER_MASK` is preserved for backwards compatibility.
* The pretty printing will now preserve comments inside array literals and function calls by
  printing the array items / function arguments on separate lines. Array literals and functions that
  do not contain comments are not affected.

### Added

* Added `Builder\Param::makeVariadic()`.

### Deprecated

* The `Node::setLine()` method has been deprecated.

Version 3.0.5 (2017-03-05)
--------------------------

### Fixed

* Name resolution of `NullableType`s is now performed earlier, so that a fully resolved signature is
  available when a function is entered. (#360)
* `Error` nodes are now considered empty, while previously they extended until the token where the
  error occurred. This made some nodes larger than expected. (#359)
* Fixed notices being thrown during error recovery in some situations. (#362)

Version 3.0.4 (2017-02-10)
--------------------------

### Fixed

* Fixed some extensibility issues in pretty printer (`pUseType()` is now public and `pPrec()` calls
  into `p()`, instead of directly dispatching to the type-specific printing method).
* Fixed notice in `bin/php-parse` script.

### Added

* Error recovery from missing semicolons is now supported in more cases.
* Error recovery from trailing commas in positions where PHP does not support them is now supported.

Version 3.0.3 (2017-02-03)
--------------------------

### Fixed

* In `"$foo[0]"` the `0` is now parsed as an `LNumber` rather than `String`. (#325)
* Ensure integers and floats are always pretty printed preserving semantics, even if the particular
  value can only be manually constructed.
* Throw a `LogicException` when trying to pretty-print an `Error` node. Previously this resulted in
  an undefined method exception or fatal error.

### Added

* [PHP 7.1] Added support for negative interpolated offsets: `"$foo[-1]"`
* Added `preserveOriginalNames` option to `NameResolver`. If this option is enabled, an
  `originalName` attribute, containing the unresolved name, will be added to each resolved name.
* Added `php-parse --with-positions` option, which dumps nodes with position information.

### Deprecated

* The XML serializer has been deprecated. In particular, the classes `Serializer\XML`,
  `Unserializer\XML`, as well as the interfaces `Serializer` and `Unserializer` are deprecated.

Version 3.0.2 (2016-12-06)
--------------------------

### Fixed

* Fixed name resolution of nullable types. (#324)
* Fixed pretty-printing of nullable types.

Version 3.0.1 (2016-12-01)
--------------------------

### Fixed

* Fixed handling of nested `list()`s: If the nested list was unkeyed, it was directly included in
  the list items. If it was keyed, it was wrapped in `ArrayItem`. Now nested `List_` nodes are
  always wrapped in `ArrayItem`s. (#321)

Version 3.0.0 (2016-11-30)
--------------------------

### Added

* Added support for dumping node positions in the NodeDumper through the `dumpPositions` option.
* Added error recovery support for `$`, `new`, `Foo::`.

Version 3.0.0-beta2 (2016-10-29)
--------------------------------

This release primarily improves our support for error recovery.

### Added

* Added `Node::setDocComment()` method.
* Added `Error::getMessageWithColumnInfo()` method.
* Added support for recovery from lexer errors.
* Added support for recovering from "special" errors (i.e. non-syntax parse errors).
* Added precise location information for lexer errors.
* Added `ErrorHandler` interface, and `ErrorHandler\Throwing` and `ErrorHandler\Collecting` as
  specific implementations. These provide a general mechanism for handling error recovery.
* Added optional `ErrorHandler` argument to `Parser::parse()`, `Lexer::startLexing()` and
  `NameResolver::__construct()`.
* The `NameResolver` now adds a `namespacedName` attribute on name nodes that cannot be statically
  resolved (unqualified unaliased function or constant names in namespaces).

### Fixed

* Fixed attribute assignment for `GroupUse` prefix and variables in interpolated strings.

### Changed

* The constants on `NameTraverserInterface` have been moved into the `NameTraverser` class.
* Due to the error handling changes, the `Parser` interface and `Lexer` API have changed.
* The emulative lexer now directly postprocesses tokens, instead of using `~__EMU__~` sequences.
  This changes the protected API of the lexer.
* The `Name::slice()` method now returns `null` for empty slices, previously `new Name([])` was
  used. `Name::concat()` now also supports concatenation with `null`.

### Removed

* Removed `Name::append()` and `Name::prepend()`. These mutable methods have been superseded by
  the immutable `Name::concat()`.
* Removed `Error::getRawLine()` and `Error::setRawLine()`. These methods have been superseded by
  `Error::getStartLine()` and `Error::setStartLine()`.
* Removed support for node cloning in the `NodeTraverser`.
* Removed `$separator` argument from `Name::toString()`.
* Removed `throw_on_error` parser option and `Parser::getErrors()` method. Use the `ErrorHandler`
  mechanism instead.

Version 3.0.0-beta1 (2016-09-16)
--------------------------------

### Added

* [7.1] Function/method and parameter builders now support PHP 7.1 type hints (void, iterable and
  nullable types).
* Nodes and Comments now implement `JsonSerializable`. The node kind is stored in a `nodeType`
  property.
* The `InlineHTML` node now has an `hasLeadingNewline` attribute, that specifies whether the
  preceding closing tag contained a newline. The pretty printer honors this attribute.
* Partial parsing of `$obj->` (with missing property name) is now supported in error recovery mode.
* The error recovery mode is now exposed in the `php-parse` script through the `--with-recovery`
  or `-r` flags.

The following changes are also part of PHP-Parser 2.1.1:

* The PHP 7 parser will now generate a parse error for `$var =& new Obj` assignments.
* Comments on free-standing code blocks will now be retained as comments on the first statement in
  the code block.

Version 3.0.0-alpha1 (2016-07-25)
---------------------------------

### Added

* [7.1] Added support for `void` and `iterable` types. These will now be represented as strings
  (instead of `Name` instances) similar to other builtin types.
* [7.1] Added support for class constant visibility. The `ClassConst` node now has a `flags` subnode
  holding the visibility modifier, as well as `isPublic()`, `isProtected()` and `isPrivate()`
  methods. The constructor changed to accept the additional subnode.
* [7.1] Added support for nullable types. These are represented using a new `NullableType` node
  with a single `type` subnode.
* [7.1] Added support for short array destructuring syntax. This means that `Array` nodes may now
  appear as the left-hand-side of assignments and foreach value targets. Additionally the array
  items may now contain `null` values if elements are skipped.
* [7.1] Added support for keys in list() destructuring. The `List` subnode `vars` has been renamed
  to `items` and now contains `ArrayItem`s instead of plain variables.
* [7.1] Added support for multi-catch. The `Catch` subnode `type` has been renamed to `types` and
  is now an array of `Name`s.
* `Name::slice()` now supports lengths and negative offsets. This brings it in line with
  `array_slice()` functionality.

### Changed

Due to PHP 7.1 support additions described above, the node structure changed as follows:

* `void` and `iterable` types are now stored as strings if the PHP 7 parser is used.
* The `ClassConst` constructor changed to accept an additional `flags` subnode.
* The `Array` subnode `items` may now contain `null` elements (destructuring).
* The `List` subnode `vars` has been renamed to `items` and now contains `ArrayItem`s instead of
  plain variables.
* The `Catch` subnode `type` has been renamed to `types` and is now an array of `Name`s.

Additionally the following changes were made:

* The `type` subnode on `Class`, `ClassMethod` and `Property` has been renamed to `flags`. The
  `type` subnode has retained for backwards compatibility and is populated to the same value as
  `flags`. However, writes to `type` will not update `flags`.
* The `TryCatch` subnode `finallyStmts` has been replaced with a `finally` subnode that holds an
  explicit `Finally` node. This allows for more accurate attribute assignment.
* The `Trait` constructor now has the same form as the `Class` and `Interface` constructors: It
  takes an array of subnodes. Unlike classes/interfaces, traits can only have a `stmts` subnode.
* The `NodeDumper` now prints class/method/property/constant modifiers, as well as the include and
  use type in a textual representation, instead of only showing the number.
* All methods on `PrettyPrinter\Standard` are now protected. Previously most of them were public.

### Removed

* Removed support for running on PHP 5.4. It is however still possible to parse PHP 5.2-5.4 code
  while running on a newer version.
* The deprecated `Comment::setLine()` and `Comment::setText()` methods have been removed.
* The deprecated `Name::set()`, `Name::setFirst()` and `Name::setLast()` methods have been removed.

Version 2.1.1 (2016-09-16)
--------------------------

### Changed

* The pretty printer will now escape all control characters in the range `\x00-\x1F` inside double
  quoted strings. If no special escape sequence is available, an octal escape will be used.
* The quality of the error recovery has been improved. In particular unterminated expressions should
  be handled more gracefully.
* The PHP 7 parser will now generate a parse error for `$var =& new Obj` assignments.
* Comments on free-standing code blocks will no be retained as comments on the first statement in
  the code block.

Version 2.1.0 (2016-04-19)
--------------------------

### Fixed

* Properly support `B""` strings (with uppercase `B`) in a number of places.
* Fixed reformatting of indented parts in a certain non-standard comment style.

### Added

* Added `dumpComments` option to node dumper, to enable dumping of comments associated with nodes.
* Added `Stmt\Nop` node, that is used to collect comments located at the end of a block or at the
  end of a file (without a following node with which they could otherwise be associated).
* Added `kind` attribute to `Expr\Exit` to distinguish between `exit` and `die`.
* Added `kind` attribute to `Scalar\LNumber` to distinguish between decimal, binary, octal and
  hexadecimal numbers.
* Added `kind` attribute to `Expr\Array` to distinguish between `array()` and `[]`.
* Added `kind` attribute to `Scalar\String` and `Scalar\Encapsed` to distinguish between
  single-quoted, double-quoted, heredoc and nowdoc string.
* Added `docLabel` attribute to `Scalar\String` and `Scalar\Encapsed`, if it is a heredoc or
  nowdoc string.
* Added start file offset information to `Comment` nodes.
* Added `setReturnType()` method to function and method builders.
* Added `-h` and `--help` options to `php-parse` script.

### Changed

* Invalid octal literals now throw a parse error in PHP 7 mode.
* The pretty printer takes all the new attributes mentioned in the previous section into account.
* The protected `AbstractPrettyPrinter::pComments()` method no longer returns a trailing newline.
* The bundled autoloader supports library files being stored in a different directory than
  `PhpParser` for easier downstream distribution.

### Deprecated

* The `Comment::setLine()` and `Comment::setText()` methods have been deprecated. Construct new
  objects instead.

### Removed

* The internal (but public) method `Scalar\LNumber::parse()` has been removed. A non-internal
  `LNumber::fromString()` method has been added instead.

Version 2.0.1 (2016-02-28)
--------------------------

### Fixed

* `declare() {}` and `declare();` are not semantically equivalent and will now result in different
  ASTs. The format case will have an empty `stmts` array, while the latter will set `stmts` to
  `null`.
* Magic constants are now supported as semi-reserved keywords.
* A shebang line like `#!/usr/bin/env php` is now allowed at the start of a namespaced file.
  Previously this generated an exception.
* The `prettyPrintFile()` method will not strip a trailing `?>` from the raw data that follows a
  `__halt_compiler()` statement.
* The `prettyPrintFile()` method will not strip an opening `<?php` if the file starts with a
  comment followed by InlineHTML.

Version 2.0.0 (2015-12-04)
--------------------------

### Changed

* String parts of encapsed strings are now represented using `Scalar\EncapsStringPart` nodes.
  Previously raw strings were used. This affects the `parts` child of `Scalar\Encaps` and
  `Expr\ShellExec`. The change has been done to allow assignment of attributes to encapsed string
  parts.

Version 2.0.0-beta1 (2015-10-21)
--------------------------------

### Fixed

* Fixed issue with too many newlines being stripped at the end of heredoc/nowdoc strings in some
  cases. (#227)

### Changed

* Update group use support to be in line with recent PHP 7.0 builds.
* Renamed `php-parse.php` to `php-parse` and registered it as a composer bin.
* Use composer PSR-4 autoloader instead of custom autoloader.
* Specify phpunit as a dev dependency.

### Added

* Added `shortArraySyntax` option to pretty printer, to print all arrays using short syntax.

Version 2.0.0-alpha1 (2015-07-14)
---------------------------------

A more detailed description of backwards incompatible changes can be found in the
[upgrading guide](UPGRADE-2.0.md).

### Removed

* Removed support for running on PHP 5.3. It is however still possible to parse PHP 5.2 and PHP 5.3
  code while running on a newer version.
* Removed legacy class name aliases. This includes the old non-namespaced class names and the old
  names for classes that were renamed for PHP 7 compatibility.
* Removed support for legacy node format. All nodes must have a `getSubNodeNames()` method now.

### Added

* Added support for remaining PHP 7 features that were not present in 1.x:
  * Group use declarations. These are represented using `Stmt\GroupUse` nodes. Furthermore a `type`
    attribute was added to `Stmt\UseUse` to handle mixed group use declarations.
  * Uniform variable syntax.
  * Generalized yield operator.
  * Scalar type declarations. These are presented using `'bool'`, `'int'`, `'float'` and `'string'`
    as the type. The PHP 5 parser also accepts these, however they'll be `Name` instances there.
  * Unicode escape sequences.
* Added `PhpParser\ParserFactory` class, which should be used to create parser instances.
* Added `Name::concat()` which concatenates two names.
* Added `Name->slice()` which takes a subslice of a name.

### Changed

* `PhpParser\Parser` is now an interface, implemented by `Parser\Php5`, `Parser\Php7` and
  `Parser\Multiple`. The `Multiple` parser will try multiple parsers, until one succeeds.
* Token constants are now defined on `PhpParser\Parser\Tokens` rather than `PhpParser\Parser`.
* The `Name->set()`, `Name->append()`, `Name->prepend()` and `Name->setFirst()` methods are
  deprecated in favor of `Name::concat()` and `Name->slice()`.
* The `NodeTraverser` no longer clones nodes by default. The old behavior can be restored by
  passing `true` to the constructor.
* The constructor for `Scalar` nodes no longer has a default value. E.g. `new LNumber()` should now
  be written as `new LNumber(0)`.

---

**This changelog only includes changes from the 2.0 series. For older changes see the
[1.x series changelog](https://github.com/nikic/PHP-Parser/blob/1.x/CHANGELOG.md) and the
[0.9 series changelog](https://github.com/nikic/PHP-Parser/blob/0.9/CHANGELOG.md).**
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            {
    "name": "nikic/php-parser",
    "type": "library",
    "description": "A PHP parser written in PHP",
    "keywords": [
        "php",
        "parser"
    ],
    "license": "BSD-3-Clause",
    "authors": [
        {
            "name": "Nikita Popov"
        }
    ],
    "require": {
        "php": ">=7.0",
        "ext-tokenizer": "*"
    },
    "require-dev": {
        "phpunit/phpunit": "^6.5 || ^7.0"
    },
    "extra": {
        "branch-alias": {
            "dev-master": "4.2-dev"
        }
    },
    "autoload": {
        "psr-4": {
            "PhpParser\\": "lib/PhpParser"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "PhpParser\\": "test/PhpParser/"
        }
    },
    "bin": [
        "bin/php-parse"
    ]
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        Copyright (c) 2011-2018 by Nikita Popov.

Some rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are
met:

    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.

    * Redistributions in binary form must reproduce the above
      copyright notice, this list of conditions and the following
      disclaimer in the documentation and/or other materials provided
      with the distribution.

    * The names of the contributors may not be used to endorse or
      promote products derived from this software without specific
      prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
"AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        PHP Parser
==========

[![Build Status](https://travis-ci.org/nikic/PHP-Parser.svg?branch=master)](https://travis-ci.org/nikic/PHP-Parser) [![Coverage Status](https://coveralls.io/repos/github/nikic/PHP-Parser/badge.svg?branch=master)](https://coveralls.io/github/nikic/PHP-Parser?branch=master)

This is a PHP 5.2 to PHP 7.3 parser written in PHP. Its purpose is to simplify static code analysis and
manipulation.

[**Documentation for version 4.x**][doc_master] (stable; for running on PHP >= 7.0; for parsing PHP 5.2 to PHP 7.3).

[Documentation for version 3.x][doc_3_x] (unsupported; for running on PHP >= 5.5; for parsing PHP 5.2 to PHP 7.2).

Features
--------

The main features provided by this library are:

 * Parsing PHP 5 and PHP 7 code into an abstract syntax tree (AST).
   * Invalid code can be parsed into a partial AST.
   * The AST contains accurate location information.
 * Dumping the AST in human-readable form.
 * Converting an AST back to PHP code.
   * Experimental: Formatting can be preserved for partially changed ASTs.
 * Infrastructure to traverse and modify ASTs.
 * Resolution of namespaced names.
 * Evaluation of constant expressions.
 * Builders to simplify AST construction for code generation.
 * Converting an AST into JSON and back.

Quick Start
-----------

Install the library using [composer](https://getcomposer.org):

    php composer.phar require nikic/php-parser

Parse some PHP code into an AST and dump the result in human-readable form:

```php
<?php
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;

$code = <<<'CODE'
<?php

function test($foo)
{
    var_dump($foo);
}
CODE;

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$dumper = new NodeDumper;
echo $dumper->dump($ast) . "\n";
```

This dumps an AST looking something like this:

```
array(
    0: Stmt_Function(
        byRef: false
        name: Identifier(
            name: test
        )
        params: array(
            0: Param(
                type: null
                byRef: false
                variadic: false
                var: Expr_Variable(
                    name: foo
                )
                default: null
            )
        )
        returnType: null
        stmts: array(
            0: Stmt_Expression(
                expr: Expr_FuncCall(
                    name: Name(
                        parts: array(
                            0: var_dump
                        )
                    )
                    args: array(
                        0: Arg(
                            value: Expr_Variable(
                                name: foo
                            )
                            byRef: false
                            unpack: false
                        )
                    )
                )
            )
        )
    )
)
```

Let's traverse the AST and perform some kind of modification. For example, drop all function bodies:

```php
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;

$traverser = new NodeTraverser();
$traverser->addVisitor(new class extends NodeVisitorAbstract {
    public function enterNode(Node $node) {
        if ($node instanceof Function_) {
            // Clean out the function body
            $node->stmts = [];
        }
    }
});

$ast = $traverser->traverse($ast);
echo $dumper->dump($ast) . "\n";
```

This gives us an AST where the `Function_::$stmts` are empty:

```
array(
    0: Stmt_Function(
        byRef: false
        name: Identifier(
            name: test
        )
        params: array(
            0: Param(
                type: null
                byRef: false
                variadic: false
                var: Expr_Variable(
                    name: foo
                )
                default: null
            )
        )
        returnType: null
        stmts: array(
        )
    )
)
```

Finally, we can convert the new AST back to PHP code:

```php
use PhpParser\PrettyPrinter;

$prettyPrinter = new PrettyPrinter\Standard;
echo $prettyPrinter->prettyPrintFile($ast);
```

This gives us our original code, minus the `var_dump()` call inside the function:

```php
<?php

function test($foo)
{
}
```

For a more comprehensive introduction, see the documentation.

Documentation
-------------

 1. [Introduction](doc/0_Introduction.markdown)
 2. [Usage of basic components](doc/2_Usage_of_basic_components.markdown)

Component documentation:

 * [Walking the AST](doc/component/Walking_the_AST.markdown)
   * Node visitors
   * Modifying the AST from a visitor
   * Short-circuiting traversals
   * Interleaved visitors
   * Simple node finding API
   * Parent and sibling references
 * [Name resolution](doc/component/Name_resolution.markdown)
   * Name resolver options
   * Name resolution context
 * [Pretty printing](doc/component/Pretty_printing.markdown)
   * Converting AST back to PHP code
   * Customizing formatting
   * Formatting-preserving code transformations
 * [AST builders](doc/component/AST_builders.markdown)
   * Fluent builders for AST nodes
 * [Lexer](doc/component/Lexer.markdown)
   * Lexer options
   * Token and file positions for nodes
   * Custom attributes
 * [Error handling](doc/component/Error_handling.markdown)
   * Column information for errors
   * Error recovery (parsing of syntactically incorrect code)
 * [Constant expression evaluation](doc/component/Constant_expression_evaluation.markdown)
   * Evaluating constant/property/etc initializers
   * Handling errors and unsupported expressions
 * [JSON representation](doc/component/JSON_representation.markdown)
   * JSON encoding and decoding of ASTs
 * [Performance](doc/component/Performance.markdown)
   * Disabling XDebug
   * Reusing objects
   * Garbage collection impact
 * [Frequently asked questions](doc/component/FAQ.markdown)
   * Parent and sibling references

 [doc_3_x]: https://github.com/nikic/PHP-Parser/tree/3.x/doc
 [doc_master]: https://github.com/nikic/PHP-Parser/tree/master/doc
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             INDX( 	 1´ˆ             (     è       Ô  Õ                *     h V     )     ¿…yJpkÕ CÔ|ÆÔ§o Ü<Õ¿…yJpkÕ@       :               
 . g i t i g n o r e   +     h X     )     ‹J~JpkÕ CÔ|ÆÔ[‘ Ü<Õ‹J~JpkÕ       œ               . t r a v i s . y m l 5     X H     )     IJpkÕŸ«ŸJpkÕŸ«ŸJpkÕIJpkÕ                        b i n ,     p Z     )     ~­€JpkÕ CÔ|ÆÔ[‘ Ü<Õ~­€JpkÕ p      Dg               C H A N G E L O G . m d       -     p \     )     MƒJpkÕ CÔ|Æ †j“ Ü<ÕMƒJpkÕ       ø               c o m p o s e r . j s o n     7     X H     )     Ú¢JpkÕKú­JpkÕKú­JpkÕKú­JpkÕ                        d o c F     ` P     )     xlKpkÕÞÍ"KpkÕÞÍ"KpkÕxlKpkÕ                        g r a m m a r N     X H     )     ÞÍ"KpkÕ£/%KpkÕ£/%KpkÕ£/%KpkÕ                        l i b .     ` P     )     «q…JpkÕ CÔ|ÆÔÕÌ• Ü<Õ«q…JpkÕ       Ø               L I C E N S E /     x b     )     áÓ‡JpkÕ CÔ|ÆÔÕÌ• Ü<ÕáÓ‡JpkÕx      r              p h p u n i t . x m l . d i s t       0     h T     )     ¥ûŽJpkÕ CÔ|ÆÔ%0˜ Ü<Õ¥ûŽJpkÕ        3              	 R E A D M E . m d     ?     ` J     )     8J±QpkÕí5ÑXpkÕí5ÑXpkÕí5ÑXpkÕ                        t e s t A D E O     h R     )     ÐÌ.[pkÕ
ô5[pkÕ
ô5[pkÕÐÌ.[pkÕ                        t e s t _ o l d 1 . 0 1     p ^     )     ¾¿“JpkÕ CÔ|ÆÔ“’š Ü<Õ¾¿“JpkÕ        ™               U P G R A D E - 1 . 0 . m d   2     p ^     )     Y"–JpkÕ CÔ|ÆÔôœ Ü< Y"–JpkÕ       P               U P G R A D E - 2 . 0 . m d   3     p ^     )     ¨„˜JpkÕ CÔ|ÆÔôœ Ü<Õ¨„˜JpkÕ        t               U P G R A D E - 3 . 0 . m d   4     p ^     )     ãæšJpkÕ CÔ|ÆÔ;WŸ Ü<ÕãæšJpkÕ       q               U P G R A D E - 4 . 0 . m d                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            Upgrading from PHP-Parser 0.9 to 1.0
====================================

### PHP version requirements

PHP-Parser now requires PHP 5.3 or newer to run. It is however still possible to *parse* PHP 5.2 source code, while
running on a newer version.

### Move to namespaced names

The library has been moved to use namespaces with the `PhpParser` vendor prefix. However, the old names using
underscores are still available as aliases, as such most code should continue running on the new version without
further changes.

Old (still works, but discouraged):

```php
$parser = new \PHPParser_Parser(new \PHPParser_Lexer_Emulative);
$prettyPrinter = new \PHPParser_PrettyPrinter_Default;
```

New:

```php
$parser = new \PhpParser\Parser(new PhpParser\Lexer\Emulative);
$prettyPrinter = new \PhpParser\PrettyPrinter\Standard;
```

Note that the `PHPParser` prefix was changed to `PhpParser`. While PHP class names are technically case-insensitive,
the autoloader will not be able to load `PHPParser\Parser` or other case variants.

Due to conflicts with reserved keywords, some class names now end with an underscore, e.g. `PHPParser_Node_Stmt_Class`
is now `PhpParser\Node\Stmt\Class_`. (But as usual, the old name is still available.)

### Changes to `Node::getType()`

The `Node::getType()` method continues to return names using underscores instead of namespace separators and also does
not contain the trailing underscore that may be present in the class name. As such its output will not change in many
cases.

However, some node classes have been moved to a different namespace or renamed, which will result in a different
`Node::getType()` output:

```
Expr_AssignBitwiseAnd => Expr_AssignOp_BitwiseAnd
Expr_AssignBitwiseOr  => Expr_AssignOp_BitwiseOr
Expr_AssignBitwiseXor => Expr_AssignOp_BitwiseXor
Expr_AssignConcat     => Expr_AssignOp_Concat
Expr_AssignDiv        => Expr_AssignOp_Div
Expr_AssignMinus      => Expr_AssignOp_Minus
Expr_AssignMod        => Expr_AssignOp_Mod
Expr_AssignMul        => Expr_AssignOp_Mul
Expr_AssignPlus       => Expr_AssignOp_Plus
Expr_AssignShiftLeft  => Expr_AssignOp_ShiftLeft
Expr_AssignShiftRight => Expr_AssignOp_ShiftRight

Expr_BitwiseAnd       => Expr_BinaryOp_BitwiseAnd
Expr_BitwiseOr        => Expr_BinaryOp_BitwiseOr
Expr_BitwiseXor       => Expr_BinaryOp_BitwiseXor
Expr_BooleanAnd       => Expr_BinaryOp_BooleanAnd
Expr_BooleanOr        => Expr_BinaryOp_BooleanOr
Expr_Concat           => Expr_BinaryOp_Concat
Expr_Div              => Expr_BinaryOp_Div
Expr_Equal            => Expr_BinaryOp_Equal
Expr_Greater          => Expr_BinaryOp_Greater
Expr_GreaterOrEqual   => Expr_BinaryOp_GreaterOrEqual
Expr_Identical        => Expr_BinaryOp_Identical
Expr_LogicalAnd       => Expr_BinaryOp_LogicalAnd
Expr_LogicalOr        => Expr_BinaryOp_LogicalOr
Expr_LogicalXor       => Expr_BinaryOp_LogicalXor
Expr_Minus            => Expr_BinaryOp_Minus
Expr_Mod              => Expr_BinaryOp_Mod
Expr_Mul              => Expr_BinaryOp_Mul
Expr_NotEqual         => Expr_BinaryOp_NotEqual
Expr_NotIdentical     => Expr_BinaryOp_NotIdentical
Expr_Plus             => Expr_BinaryOp_Plus
Expr_ShiftLeft        => Expr_BinaryOp_ShiftLeft
Expr_ShiftRight       => Expr_BinaryOp_ShiftRight
Expr_Smaller          => Expr_BinaryOp_Smaller
Expr_SmallerOrEqual   => Expr_BinaryOp_SmallerOrEqual

Scalar_ClassConst     => Scalar_MagicConst_Class
Scalar_DirConst       => Scalar_MagicConst_Dir
Scalar_FileConst      => Scalar_MagicConst_File
Scalar_FuncConst      => Scalar_MagicConst_Function
Scalar_LineConst      => Scalar_MagicConst_Line
Scalar_MethodConst    => Scalar_MagicConst_Method
Scalar_NSConst        => Scalar_MagicConst_Namespace
Scalar_TraitConst     => Scalar_MagicConst_Trait
```

These changes may affect custom pretty printers and code comparing the return value of `Node::getType()` to specific
strings.

### Miscellaneous

  * The classes `Template` and `TemplateLoader` have been removed. You should use some other [code generation][code_gen]
    project built on top of PHP-Parser instead.

  * The `PrettyPrinterAbstract::pStmts()` method now emits a leading newline if the statement list is not empty.
    Custom pretty printers should remove the explicit newline before `pStmts()` calls.

    Old:

    ```php
    public function pStmt_Trait(PHPParser_Node_Stmt_Trait $node) {
        return 'trait ' . $node->name
             . "\n" . '{' . "\n" . $this->pStmts($node->stmts) . "\n" . '}';
    }
    ```

    New:

    ```php
    public function pStmt_Trait(Stmt\Trait_ $node) {
        return 'trait ' . $node->name
             . "\n" . '{' . $this->pStmts($node->stmts) . "\n" . '}';
    }
    ```

  [code_gen]: https://github.com/nikic/PHP-Parser/wiki/Projects-using-the-PHP-Parser#code-generation                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       Upgrading from PHP-Parser 1.x to 2.0
====================================

### PHP version requirements

PHP-Parser now requires PHP 5.4 or newer to run. It is however still possible to *parse* PHP 5.2 and
PHP 5.3 source code, while running on a newer version.

### Creating a parser instance

Parser instances should now be created through the `ParserFactory`. Old direct instantiation code
will not work, because the parser class was renamed.

Old:

```php
use PhpParser\Parser, PhpParser\Lexer;
$parser = new Parser(new Lexer\Emulative);
```

New:

```php
use PhpParser\ParserFactory;
$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
```

The first argument to `ParserFactory` determines how different PHP versions are handled. The
possible values are:

 * `ParserFactory::PREFER_PHP7`: Try to parse code as PHP 7. If this fails, try to parse it as PHP 5.
 * `ParserFactory::PREFER_PHP5`: Try to parse code as PHP 5. If this fails, try to parse it as PHP 7.
 * `ParserFactory::ONLY_PHP7`: Parse code as PHP 7.
 * `ParserFactory::ONLY_PHP5`: Parse code as PHP 5.

For most practical purposes the difference between `PREFER_PHP7` and `PREFER_PHP5` is mainly whether
a scalar type hint like `string` will be stored as `'string'` (PHP 7) or as `new Name('string')`
(PHP 5).

To use a custom lexer, pass it as the second argument to the `create()` method:

```php
use PhpParser\ParserFactory;
$lexer = new MyLexer;
$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7, $lexer);
```

### Rename of the `PhpParser\Parser` class

`PhpParser\Parser` is now an interface, which is implemented by `Parser\Php5`, `Parser\Php7` and
`Parser\Multiple`. Parser tokens are now defined in `Parser\Tokens`. If you use the `ParserFactory`
described above to create your parser instance, these changes should have no further impact on you.

### Removal of legacy aliases

All legacy aliases for classes have been removed. This includes the old non-namespaced `PHPParser_`
classes, as well as the classes that had to be renamed for PHP 7 support.

### Deprecations

The `set()`, `setFirst()`, `append()` and `prepend()` methods of the `Node\Name` class have been
deprecated. Instead `Name::concat()` and `Name->slice()` should be used.

### Miscellaneous

* The `NodeTraverser` no longer clones nodes by default. If you want to restore the old behavior,
  pass `true` to the constructor.
* The legacy node format has been removed. If you use custom nodes, they are now expected to
  implement a `getSubNodeNames()` method.
* The default value for `Scalar` node constructors was removed. This means that something like
  `new LNumber()` should be replaced by `new LNumber(0)`.
* String parts of encapsed strings are now represented using `Scalar\EncapsStringPart` nodes, while
  previously raw strings were used. This affects the `parts` child of `Scalar\Encaps` and
  `Expr\ShellExec`.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                Upgrading from PHP-Parser 2.x to 3.0
====================================

The backwards-incompatible changes in this release may be summarized as follows:

 * The specific details of the node representation have changed in some cases, primarily to
   accommodate new PHP 7.1 features.
 * There have been significant changes to the error recovery implementation. This may affect you,
   if you used the error recovery mode or have a custom lexer implementation.
 * A number of deprecated methods were removed.

### PHP version requirements

PHP-Parser now requires PHP 5.5 or newer to run. It is however still possible to *parse* PHP 5.2,
5.3 and 5.4 source code, while running on a newer version.

### Changes to the node structure

The following changes are likely to require code changes if the respective nodes are used:

 * The `List` subnode `vars` has been renamed to `items` and now contains `ArrayItem`s instead of
   plain variables.
 * The `Catch` subnode `type` has been renamed to `types` and is now an array of `Name`s.
 * The `TryCatch` subnode `finallyStmts` has been replaced with a `finally` subnode that holds an
   explicit `Finally` node.
 * The `type` subnode on `Class`, `ClassMethod` and `Property` has been renamed to `flags`. The
   `type` subnode has retained for backwards compatibility and is populated to the same value as
   `flags`. However, writes to `type` will not update `flags` and use of `type` is discouraged.

The following changes are unlikely to require code changes:

 * The `ClassConst` constructor changed to accept an additional `flags` subnode.
 * The `Trait` constructor now has the same form as the `Class` and `Interface` constructors: It
   takes an array of subnodes. Unlike classes/interfaces, traits can only have a `stmts` subnode.
 * The `Array` subnode `items` may now contain `null` elements (due to destructuring).
 * `void` and `iterable` types are now stored as strings if the PHP 7 parser is used. Previously
   these would have been represented as `Name` instances.

### Changes to error recovery mode

Previously, error recovery mode was enabled by setting the `throwOnError` option to `false` when
creating the parser, while collected errors were retrieved using the `getErrors()` method:

```php
$lexer = ...;
$parser = (new ParserFactory)->create(ParserFactor::ONLY_PHP7, $lexer, [
    'throwOnError' => true,
]);

$stmts = $parser->parse($code);
$errors = $parser->getErrors();
if ($errors) {
    handleErrors($errors);
}
processAst($stmts);
```

Both the `throwOnError` option and the `getErrors()` method have been removed in PHP-Parser 3.0.
Instead an instance of `ErrorHandler\Collecting` should be passed to the `parse()` method:

```php
$lexer = ...;
$parser = (new ParserFactory)->create(ParserFactor::ONLY_PHP7, $lexer);

$errorHandler = new ErrorHandler\Collecting;
$stmts = $parser->parse($code, $errorHandler);
if ($errorHandler->hasErrors()) {
    handleErrors($errorHandler->getErrors());
}
processAst($stmts);
```

#### Multiple parser fallback in error recovery mode

As a result of this change, if a `Multiple` parser is used (e.g. through the `ParserFactory` using
`PREFER_PHP7` or `PREFER_PHP5`), it will now return the result of the first *non-throwing* parse. As
parsing never throws in error recovery mode, the result from the first parser will always be
returned.

The PHP 7 parser is a superset of the PHP 5 parser, with the exceptions that `=& new` and
`global $$foo->bar` are not supported (other differences are in representation only). The PHP 7
parser will be able to recover from the error in both cases. For this reason, this change will
likely pass unnoticed if you do not specifically test for this syntax.

It is possible to restore the precise previous behavior with the following code:

```php
$lexer = ...;
$parser7 = new Parser\Php7($lexer);
$parser5 = new Parser\Php5($lexer);

$errors7 = new ErrorHandler\Collecting();
$stmts7 = $parser7->parse($code, $errors7);
if ($errors7->hasErrors()) {
    $errors5 = new ErrorHandler\Collecting();
    $stmts5 = $parser5->parse($code, $errors5);
    if (!$errors5->hasErrors()) {
        // If PHP 7 parse has errors but PHP 5 parse has no errors, use PHP 5 result
        return [$stmts5, $errors5];
    }
}
// If PHP 7 succeeds or both fail use PHP 7 result
return [$stmts7, $errors7];
```

#### Error handling in the lexer

In order to support recovery from lexer errors, the signature of the `startLexing()` method changed
to optionally accept an `ErrorHandler`:

```php
// OLD
public function startLexing($code);
// NEW
public function startLexing($code, ErrorHandler $errorHandler = null);
```

If you use a custom lexer with overridden `startLexing()` method, it needs to be changed to accept
the extra parameter. The value should be passed on to the parent method.

#### Error checks in node constructors

The constructors of certain nodes used to contain additional checks for semantic errors, such as
creating a try block without either catch or finally. These checks have been moved from the node
constructors into the parser. This allows recovery from such errors, as well as representing the
resulting (invalid) AST.

This means that certain error conditions are no longer checked for manually constructed nodes.

### Removed methods, arguments, options

The following methods, arguments or options have been removed:

 * `Comment::setLine()`, `Comment::setText()`: Create new `Comment` instances instead.
 * `Name::set()`, `Name::setFirst()`, `Name::setLast()`, `Name::append()`, `Name::prepend()`:
    Use `Name::concat()` in combination with `Name::slice()` instead.
 * `Error::getRawLine()`, `Error::setRawLine()`. Use `Error::getStartLine()` and
   `Error::setStartLine()` instead.
 * `Parser::getErrors()`. Use `ErrorHandler\Collecting` instead.
 * `$separator` argument of `Name::toString()`. Use `strtr()` instead, if you really need it.
 * `$cloneNodes` argument of `NodeTraverser::__construct()`. Explicitly clone nodes in the visitor
   instead.
 * `throwOnError` parser option. Use `ErrorHandler\Collecting` instead.

### Miscellaneous

 * The `NameResolver` will now resolve unqualified function and constant names in the global
   namespace into fully qualified names. For example `foo()` in the global namespace resolves to
   `\foo()`. For names where no static resolution is possible, a `namespacedName` attribute is
   added now, containing the namespaced variant of the name.
 * All methods on `PrettyPrinter\Standard` are now protected. Previously most of them were public.
   The pretty printer should only be invoked using the `prettyPrint()`, `prettyPrintFile()` and
   `prettyPrintExpr()` methods.
 * The node dumper now prints numeric values that act as enums/flags in a string representation.
   If node dumper results are used in tests, updates may be needed to account for this.
 * The constants on `NameTraverserInterface` have been moved into the `NameTraverser` class.
 * The emulative lexer now directly postprocesses tokens, instead of using `~__EMU__~` sequences.
   This changes the protected API of the emulative lexer.
 * The `Name::slice()` method now returns `null` for empty slices, previously `new Name([])` was
   used. `Name::concat()` now also supports concatenation with `null`.
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            Upgrading from PHP-Parser 3.x to 4.0
====================================

### PHP version requirements

PHP-Parser now requires PHP 7.0 or newer to run. It is however still possible to *parse* PHP 5.2-5.6
source code, while running on a newer version.

HHVM is no longer actively supported.

### Changes to the node structure

* Many subnodes that previously held simple strings now store `Identifier` nodes instead (or
  `VarLikeIdentifier` nodes if they have form `$ident`). The constructors of the affected nodes will
  automatically convert strings to `Identifier`s and `Identifier`s implement `__toString()`. As such
  some code continues to work without changes, but anything using `is_string()`, type-strict
  comparisons or strict-mode may require adjustment. The following is an exhaustive list of all
  affected subnodes:

   * `Const_::$name`
   * `NullableType::$type` (for simple types)
   * `Param::$type` (for simple types)
   * `Expr\ClassConstFetch::$name`
   * `Expr\Closure::$returnType` (for simple types)
   * `Expr\MethodCall::$name`
   * `Expr\PropertyFetch::$name`
   * `Expr\StaticCall::$name`
   * `Expr\StaticPropertyFetch::$name` (uses `VarLikeIdentifier`)
   * `Stmt\Class_::$name`
   * `Stmt\ClassMethod::$name`
   * `Stmt\ClassMethod::$returnType` (for simple types)
   * `Stmt\Function_::$name`
   * `Stmt\Function_::$returnType` (for simple types)
   * `Stmt\Goto_::$name`
   * `Stmt\Interface_::$name`
   * `Stmt\Label::$name`
   * `Stmt\PropertyProperty::$name` (uses `VarLikeIdentifier`)
   * `Stmt\TraitUseAdaptation\Alias::$method`
   * `Stmt\TraitUseAdaptation\Alias::$newName`
   * `Stmt\TraitUseAdaptation\Precedence::$method`
   * `Stmt\Trait_::$name`
   * `Stmt\UseUse::$alias`

* Expression statements (`expr;`) are now represented using a `Stmt\Expression` node. Previously
  these statements were directly represented as their constituent expression.
* The `name` subnode of `Param` has been renamed to `var` and now contains a `Variable` rather than
  a plain string.
* The `name` subnode of `StaticVar` has been renamed to `var` and now contains a `Variable` rather
  than a plain string.
* The `var` subnode of `ClosureUse` now contains a `Variable` rather than a plain string.
* The `var` subnode of `Catch_` now contains a `Variable` rather than a plain string.
* The `alias` subnode of `UseUse` is now `null` if no explicit alias is given. As such,
  `use Foo\Bar` and `use Foo\Bar as Bar` are now represented differently. The `getAlias()` method
  can be used to get the effective alias, even if it is not explicitly given.

### Miscellaneous

* The indentation handling in the pretty printer has been changed (this is only relevant if you
  extend the pretty printer). Previously indentation was automatic, and parts were excluded using
  `pNoindent()`. Now no-indent is the default and newlines that require indentation should use
  `$this->nl`.

### Removed functionality

* Removed `type` subnode on `Class_`, `ClassMethod` and `Property` nodes. Use `flags` instead.
* The `ClassConst::isStatic()` method has been removed. Constants cannot have a static modifier.
* The `NodeTraverser` no longer accepts `false` as a return value from a `leaveNode()` method.
  `NodeTraverser::REMOVE_NODE` should be returned instead.
* The `Node::setLine()` method has been removed. If you really need to, you can use `setAttribute()`
  instead.
* The misspelled `Class_::VISIBILITY_MODIFER_MASK` constant has been dropped in favor of
  `Class_::VISIBILITY_MODIFIER_MASK`.
* The XML serializer has been removed. As such, the classes `Serializer\XML`, and
  `Unserializer\XML`, as well as the interfaces `Serializer` and `Unserializer` no longer exist.
* The `BuilderAbstract` class has been removed. It's functionality is moved into `BuilderHelpers`.
  However, this is an internal class and should not be used directly.
* The `Autoloader` class has been removed in favor of relying on the Composer autoloader.
                                                                                                                                               #!/usr/bin/env php
<?php

foreach ([__DIR__ . '/../../../autoload.php', __DIR__ . '/../vendor/autoload.php'] as $file) {
    if (file_exists($file)) {
        require $file;
        break;
    }
}

ini_set('xdebug.max_nesting_level', 3000);

// Disable XDebug var_dump() output truncation
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_depth', -1);

list($operations, $files, $attributes) = parseArgs($argv);

/* Dump nodes by default */
if (empty($operations)) {
    $operations[] = 'dump';
}

if (empty($files)) {
    showHelp("Must specify at least one file.");
}

$lexer = new PhpParser\Lexer\Emulative(['usedAttributes' => [
    'startLine', 'endLine', 'startFilePos', 'endFilePos', 'comments'
]]);
$parser = (new PhpParser\ParserFactory)->create(
    PhpParser\ParserFactory::PREFER_PHP7,
    $lexer
);
$dumper = new PhpParser\NodeDumper([
    'dumpComments' => true,
    'dumpPositions' => $attributes['with-positions'],
]);
$prettyPrinter = new PhpParser\PrettyPrinter\Standard;

$traverser = new PhpParser\NodeTraverser();
$traverser->addVisitor(new PhpParser\NodeVisitor\NameResolver);

foreach ($files as $file) {
    if (strpos($file, '<?php') === 0) {
        $code = $file;
        echo "====> Code $code\n";
    } else {
        if (!file_exists($file)) {
            die("File $file does not exist.\n");
        }

        $code = file_get_contents($file);
        echo "====> File $file:\n";
    }

    if ($attributes['with-recovery']) {
        $errorHandler = new PhpParser\ErrorHandler\Collecting;
        $stmts = $parser->parse($code, $errorHandler);
        foreach ($errorHandler->getErrors() as $error) {
            $message = formatErrorMessage($error, $code, $attributes['with-column-info']);
            echo $message . "\n";
        }
        if (null === $stmts) {
            continue;
        }
    } else {
        try {
            $stmts = $parser->parse($code);
        } catch (PhpParser\Error $error) {
            $message = formatErrorMessage($error, $code, $attributes['with-column-info']);
            die($message . "\n");
        }
    }

    foreach ($operations as $operation) {
        if ('dump' === $operation) {
            echo "==> Node dump:\n";
            echo $dumper->dump($stmts, $code), "\n";
        } elseif ('pretty-print' === $operation) {
            echo "==> Pretty print:\n";
            echo $prettyPrinter->prettyPrintFile($stmts), "\n";
        } elseif ('json-dump' === $operation) {
            echo "==> JSON dump:\n";
            echo json_encode($stmts, JSON_PRETTY_PRINT), "\n";
        } elseif ('var-dump' === $operation) {
            echo "==> var_dump():\n";
            var_dump($stmts);
        } elseif ('resolve-names' === $operation) {
            echo "==> Resolved names.\n";
            $stmts = $traverser->traverse($stmts);
        }
    }
}

function formatErrorMessage(PhpParser\Error $e, $code, $withColumnInfo) {
    if ($withColumnInfo && $e->hasColumnInfo()) {
        return $e->getMessageWithColumnInfo($code);
    } else {
        return $e->getMessage();
    }
}

function showHelp($error = '') {
    if ($error) {
        echo $error . "\n\n";
    }
    die(<<<OUTPUT
Usage: php-parse [operations] file1.php [file2.php ...]
   or: php-parse [operations] "<?php code"
Turn PHP source code into an abstract syntax tree.

Operations is a list of the following options (--dump by default):

    -d, --dump              Dump nodes using NodeDumper
    -p, --pretty-print      Pretty print file using PrettyPrinter\Standard
    -j, --json-dump         Print json_encode() result
        --var-dump          var_dump() nodes (for exact structure)
    -N, --resolve-names     Resolve names using NodeVisitor\NameResolver
    -c, --with-column-info  Show column-numbers for errors (if available)
    -P, --with-positions    Show positions in node dumps
    -r, --with-recovery     Use parsing with error recovery
    -h, --help              Display this page

Example:
    php-parse -d -p -N -d file.php

    Dumps nodes, pretty prints them, then resolves names and dumps them again.


OUTPUT
    );
}

function parseArgs($args) {
    $operations = [];
    $files = [];
    $attributes = [
        'with-column-info' => false,
        'with-positions' => false,
        'with-recovery' => false,
    ];

    array_shift($args);
    $parseOptions = true;
    foreach ($args as $arg) {
        if (!$parseOptions) {
            $files[] = $arg;
            continue;
        }

        switch ($arg) {
            case '--dump':
            case '-d':
                $operations[] = 'dump';
                break;
            case '--pretty-print':
            case '-p':
                $operations[] = 'pretty-print';
                break;
            case '--json-dump':
            case '-j':
                $operations[] = 'json-dump';
                break;
            case '--var-dump':
                $operations[] = 'var-dump';
                break;
            case '--resolve-names':
            case '-N';
                $operations[] = 'resolve-names';
                break;
            case '--with-column-info':
            case '-c';
                $attributes['with-column-info'] = true;
                break;
            case '--with-positions':
            case '-P':
                $attributes['with-positions'] = true;
                break;
            case '--with-recovery':
            case '-r':
                $attributes['with-recovery'] = true;
                break;
            case '--help':
            case '-h';
                showHelp();
                break;
            case '--':
                $parseOptions = false;
                break;
            default:
                if ($arg[0] === '-') {
                    showHelp("Invalid operation $arg.");
                } else {
                    $files[] = $arg;
                }
        }
    }

    return [$operations, $files, $attributes];
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    Introduction
============

This project is a PHP 5.2 to PHP 7.3 parser **written in PHP itself**.

What is this for?
-----------------

A parser is useful for [static analysis][0], manipulation of code and basically any other
application dealing with code programmatically. A parser constructs an [Abstract Syntax Tree][1]
(AST) of the code and thus allows dealing with it in an abstract and robust way.

There are other ways of processing source code. One that PHP supports natively is using the
token stream generated by [`token_get_all`][2]. The token stream is much more low level than
the AST and thus has different applications: It allows to also analyze the exact formatting of
a file. On the other hand the token stream is much harder to deal with for more complex analysis.
For example, an AST abstracts away the fact that, in PHP, variables can be written as `$foo`, but also
as `$$bar`, `${'foobar'}` or even `${!${''}=barfoo()}`. You don't have to worry about recognizing
all the different syntaxes from a stream of tokens.

Another question is: Why would I want to have a PHP parser *written in PHP*? Well, PHP might not be
a language especially suited for fast parsing, but processing the AST is much easier in PHP than it
would be in other, faster languages like C. Furthermore the people most probably wanting to do
programmatic PHP code analysis are incidentally PHP developers, not C developers.

What can it parse?
------------------

The parser supports parsing PHP 5.2-7.3.

As the parser is based on the tokens returned by `token_get_all` (which is only able to lex the PHP
version it runs on), additionally a wrapper for emulating tokens from newer versions is provided.
This allows to parse PHP 7.3 source code running on PHP 7.0, for example. This emulation is somewhat
hacky and not perfect, but it should work well on any sane code.

What output does it produce?
----------------------------

The parser produces an [Abstract Syntax Tree][1] (AST) also known as a node tree. How this looks
can best be seen in an example. The program `<?php echo 'Hi', 'World';` will give you a node tree
roughly looking like this:

```
array(
    0: Stmt_Echo(
        exprs: array(
            0: Scalar_String(
                value: Hi
            )
            1: Scalar_String(
                value: World
            )
        )
    )
)
```

Thi