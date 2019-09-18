 static
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->callMacro($method, $parameters);
        }

        $arg = count($parameters) === 0 ? 1 : $parameters[0];

        switch (Carbon::singularUnit(rtrim($method, 'z'))) {
            case 'year':
                $this->years = $arg;
                break;

            case 'month':
                $this->months = $arg;
                break;

            case 'week':
                $this->dayz = $arg * static::getDaysPerWeek();
                break;

            case 'day':
                $this->dayz = $arg;
                break;

            case 'hour':
                $this->hours = $arg;
                break;

            case 'minute':
                $this->minutes = $arg;
                break;

            case 'second':
                $this->seconds = $arg;
                break;

            case 'milli':
            case 'millisecond':
                $this->milliseconds = $arg;
                break;

            case 'micro':
            case 'microsecond':
                $this->microseconds = $arg;
                break;

            default:
                if ($this->localStrictModeEnabled ?? Carbon::isStrictModeEnabled()) {
                    throw new BadMethodCallException(sprintf("Unknown fluent setter '%s'", $method));
                }
        }

        return $this;
    }

    protected function getForHumansParameters($syntax = null, $short = false, $parts = -1, $options = null)
    {
        $join = ' ';
        $aUnit = false;
        if (is_array($syntax)) {
            extract($syntax);
        } else {
            if (is_int($short)) {
                $parts = $short;
                $short = false;
            }
            if (is_bool($syntax)) {
                $short = $syntax;
                $syntax = CarbonInterface::DIFF_ABSOLUTE;
            }
        }
        if (is_null($syntax)) {
            $syntax = CarbonInterface::DIFF_ABSOLUTE;
        }
        if ($parts === -1) {
            $parts = INF;
        }
        if (is_null($options)) {
            $options = static::getHumanDiffOptions();
        }
        if ($join === true) {
            $default = $this->getTranslationMessage('list.0') ?? $this->getTranslationMessage('list') ?? ' ';
            $join = [
                $default,
                $this->getTranslationMessage('list.1') ?? $default,
            ];
        }
        if (is_array($join)) {
            [$default, $last] = $join;

            $join = function ($list) use ($default, $last) {
                if (count($list) < 2) {
                    return implode('', $list);
                }

                $end = array_pop($list);

                return implode($default, $list).$last.$end;
            };
        }
        if (is_string($join)) {
            $glue = $join;
            $join = function ($list) use ($glue) {
                return implode($glue, $list);
            };
        }

        return [$syntax, $short, $parts, $options, $join, $aUnit];
    }

    /**
     * Get the current interval in a human readable format in the current locale.
     *
     * @example
     * ```
     * echo CarbonInterval::fromString('4d 3h 40m')->forHumans() . "\n";
     * echo CarbonInterval::fromString('4d 3h 40m')->forHumans(['parts' => 2]) . "\n";
     * echo CarbonInterval::fromString('4d 3h 40m')->forHumans(['parts' => 3, 'join' => true]) . "\n";
     * echo CarbonInterval::fromString('4d 3h 40m')->forHumans(['short' => true]) . "\n";
     * echo CarbonInterval::fromString('1d 24h')->forHumans(['join' => ' or ']) . "\n";
     * ```
     *
     * @param int|array $syntax  if array passed, parameters will be extracted from it, the array may contains:
     *                           - 'syntax' entry (see below)
     *                           - 'short' entry (see below)
     *                           - 'parts' entry (see below)
     *                           - 'options' entry (see below)
     *                           - 'aUnit' entry, prefer "an hour" over "1 hour" if true
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
     * @param int       $parts   maximum number of parts to display (default value: -1: no limits)
     * @param int       $options human diff options
     *
     * @return string
     */
    public function forHumans($syntax = null, $short = false, $parts = -1, $options = null)
    {
        [$syntax, $short, $parts, $options, $join, $aUnit] = $this->getForHumansParameters($syntax, $short, $parts, $options);

        $interval = [];
        $syntax = (int) ($syntax === null ? CarbonInterface::DIFF_ABSOLUTE : $syntax);
        $absolute = $syntax === CarbonInterface::DIFF_ABSOLUTE;
        $relativeToNow = $syntax === CarbonInterface::DIFF_RELATIVE_TO_NOW;
        $count = 1;
        $unit = $short ? 's' : 'second';

        /** @var \Symfony\Component\Translation\Translator $translator */
        $translator = $this->getLocalTranslator();

        $diffIntervalArray = [
            ['value' => $this->years,            'unit' => 'year',   'unitShort' => 'y'],
            ['value' => $this->months,           'unit' => 'month',  'unitShort' => 'm'],
            ['value' => $this->weeks,            'unit' => 'week',   'unitShort' => 'w'],
            ['value' => $this->daysExcludeWeeks, 'unit' => 'day',    'unitShort' => 'd'],
            ['value' => $this->hours,            'unit' => 'hour',   'unitShort' => 'h'],
            ['value' => $this->minutes,          'unit' => 'minute', 'unitShort' => 'min'],
            ['value' => $this->seconds,          'unit' => 'second', 'unitShort' => 's'],
        ];

        $transChoice = function ($short, $unitData) use ($translator, $aUnit) {
            $count = $unitData['value'];

            if ($short) {
                $result = $this->translate($unitData['unitShort'], [], $count, $translator);

                if ($result !== $unitData['unitShort']) {
                    return $result;
                }
            } elseif ($aUnit) {
                $key = 'a_'.$unitData['unit'];
                $result = $this->translate($key, [], $count, $translator);

                if ($result !== $key) {
                    return $result;
                }
            }

            return $this->translate($unitData['unit'], [], $count, $translator);
        };

        foreach ($diffIntervalArray as $diffIntervalData) {
            if ($diffIntervalData['value'] > 0) {
                $unit = $short ? $diffIntervalData['unitShort'] : $diffIntervalData['unit'];
                $count = $diffIntervalData['value'];
                $interval[] = $transChoice($short, $diffIntervalData);
            }

            // break the loop after we get the required number of parts in array
            if (count($interval) >= $parts) {
                break;
            }
        }

        if (count($interval) === 0) {
            if ($relativeToNow && $options & CarbonInterface::JUST_NOW) {
                $key = 'diff_now';
                $translation = $this->translate($key, [], null, $translator);
                if ($translation !== $key) {
                    return $translation;
                }
            }
            $count = $options & CarbonInterface::NO_ZERO_DIFF ? 1 : 0;
            $unit = $short ? 's' : 'second';
            $interval[] = $this->translate($unit, [], $count, $translator);
        }

        // join the interval parts by a space
        $time = $join($interval);

        unset($diffIntervalArray, $interval);

        if ($absolute) {
            return $time;
        }

        $isFuture = $this->invert === 1;

        $transId = $relativeToNow ? ($isFuture ? 'from_now' : 'ago') : ($isFuture ? 'after' : 'before');

        if ($parts === 1) {
            if ($relativeToNow && $unit === 'day') {
                if ($count === 1 && $options & CarbonInterface::ONE_DAY_WORDS) {
                    $key = $isFuture ? 'diff_tomorrow' : 'diff_yesterday';
                    $translation = $this->translate($key, [], null, $translator);
                    if ($translation !== $key) {
                        return $translation;
                    }
                }
                if ($count === 2 && $options & CarbonInterface::TWO_DAY_WORDS) {
                    $key = $isFuture ? 'diff_after_tomorrow' : 'diff_before_yesterday';
                    $translation = $this->translate($key, [], null, $translator);
                    if ($translation !== $key) {
                        return $translation;
                    }
                }
            }
            // Some languages have special pluralization for past and future tense.
            $key = $unit.'_'.$transId;
            if ($key !== $this->translate($key, [], null, $translator)) {
                $time = $this->translate($key, [], $count, $translator);
            }
        }

        return $this->translate($transId, [':time' => $time], null, $translator);
    }

    /**
     * Format the instance as a string using the forHumans() function.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->forHumans();
    }

    /**
     * Convert the interval to a CarbonPeriod.
     *
     * @return CarbonPeriod
     */
    public function toPeriod(...$params)
    {
        return CarbonPeriod::create($this, ...$params);
    }

    /**
     * Invert the interval.
     *
     * @return $this
     */
    public function invert()
    {
        $this->invert = $this->invert ? 0 : 1;

        return $this;
    }

    protected function solveNegativeInterval()
    {
        if (!$this->isEmpty() && $this->years <= 0 && $this->months <= 0 && $this->dayz <= 0 && $this->hours <= 0 && $this->minutes <= 0 && $this->seconds <= 0 && $this->microseconds <= 0) {
            $this->years *= -1;
            $this->months *= -1;
            $this->dayz *= -1;
            $this->hours *= -1;
            $this->minutes *= -1;
            $this->seconds *= -1;
            $this->microseconds *= -1;
            $this->invert();
        }

        return $this;
    }

    /**
     * Add the passed interval to the current instance.
     *
     * @param string|DateInterval $unit
     * @param int                 $value
     *
     * @return static
     */
    public function add($unit, $value = 1)
    {
        if (is_numeric($unit)) {
            $_unit = $value;
            $value = $unit;
            $unit = $_unit;
            unset($_unit);
        }

        if (is_string($unit) && !preg_match('/^\s*\d/', $unit)) {
            $unit = "$value $unit";
            $value = 1;
        }

        $interval = static::make($unit);

        if (!$interval) {
            throw new InvalidArgumentException('This type of data cannot be added/subtracted.');
        }

        if ($value !== 1) {
            $interval->times($value);
        }
        $sign = ($this->invert === 1) !== ($interval->invert === 1) ? -1 : 1;
        $this->years += $interval->y * $sign;
        $this->months += $interval->m * $sign;
        $this->dayz += ($interval->days === false ? $interval->d : $interval->days) * $sign;
        $this->hours += $interval->h * $sign;
        $this->minutes += $interval->i * $sign;
        $this->seconds += $interval->s * $sign;
        $this->microseconds += $interval->microseconds * $sign;

        $this->solveNegativeInterval();

        return $this;
    }

    /**
     * Subtract the passed interval to the current instance.
     *
     * @param string|DateInterval $unit
     * @param int                 $value
     *
     * @return static
     */
    public function sub($unit, $value = 1)
    {
        if (is_numeric($unit)) {
            $_unit = $value;
            $value = $unit;
            $unit = $_unit;
            unset($_unit);
        }

        return $this->add($unit, -floatval($value));
    }

    /**
     * Subtract the passed interval to the current instance.
     *
     * @param string|DateInterval $unit
     * @param int                 $value
     *
     * @return static
     */
    public function subtract($unit, $value = 1)
    {
        return $this->sub($unit, $value);
    }

    /**
     * Multiply current instance given number of times
     *
     * @param float|int $factor
     *
     * @return $this
     */
    public function times($factor)
    {
        if ($factor < 0) {
            $this->invert = $this->invert ? 0 : 1;
            $factor = -$factor;
        }

        $this->years = (int) round($this->years * $factor);
        $this->months = (int) round($this->months * $factor);
        $this->dayz = (int) round($this->dayz * $factor);
        $this->hours = (int) round($this->hours * $factor);
        $this->minutes = (int) round($this->minutes * $factor);
        $this->seconds = (int) round($this->seconds * $factor);
        $this->microseconds = (int) round($this->microseconds * $factor);

        return $this;
    }

    /**
     * Get the interval_spec string of a date interval.
     *
     * @param DateInterval $interval
     *
     * @return string
     */
    public static function getDateIntervalSpec(DateInterval $interval)
    {
        $date = array_filter([
            static::PERIOD_YEARS => abs($interval->y),
            static::PERIOD_MONTHS => abs($interval->m),
            static::PERIOD_DAYS => abs($interval->d),
        ]);

        $time = array_filter([
            static::PERIOD_HOURS => abs($interval->h),
            static::PERIOD_MINUTES => abs($interval->i),
            static::PERIOD_SECONDS => abs($interval->s),
        ]);

        $specString = static::PERIOD_PREFIX;

        foreach ($date as $key => $value) {
            $specString .= $value.$key;
        }

        if (count($time) > 0) {
            $specString .= static::PERIOD_TIME_PREFIX;
            foreach ($time as $key => $value) {
                $specString .= $value.$key;
            }
        }

        return $specString === static::PERIOD_PREFIX ? 'PT0S' : $specString;
    }

    /**
     * Get the interval_spec string.
     *
     * @return string
     */
    public function spec()
    {
        return static::getDateIntervalSpec($this);
    }

    /**
     * Comparing 2 date intervals.
     *
     * @param DateInterval $a
     * @param DateInterval $b
     *
     * @return int
     */
    public static function compareDateIntervals(DateInterval $a, DateInterval $b)
    {
        $current = Carbon::now();
        $passed = $current->copy()->add($b);
        $current->add($a);

        if ($current < $passed) {
            return -1;
        }
        if ($current > $passed) {
            return 1;
        }

        return 0;
    }

    /**
     * Comparing with passed interval.
     *
     * @param DateInterval $interval
     *
     * @return int
     */
    public function compare(DateInterval $interval)
    {
        return static::compareDateIntervals($this, $interval);
    }

    /**
     * Convert overflowed values into bigger units.
     *
     * @return $this
     */
    public function cascade()
    {
        foreach (static::getFlipCascadeFactors() as $source => [$target, $factor]) {
            if ($source === 'dayz' && $target === 'weeks') {
                continue;
            }

            $value = $this->$source;
            $this->$source = $modulo = ($factor + ($value % $factor)) % $factor;
            $this->$target += ($value - $modulo) / $factor;
            if ($this->$source > 0 && $this->$target < 0) {
                $this->$source -= $factor;
                $this->$target++;
            }
        }

        return $this->solveNegativeInterval();
    }

    /**
     * Get amount of given unit equivalent to the interval.
     *
     * @param string $unit
     *
     * @throws \InvalidArgumentException
     *
     * @return float
     */
    public function total($unit)
    {
        $realUnit = $unit = strtolower($unit);

        if (in_array($unit, ['days', 'weeks'])) {
            $realUnit = 'dayz';
        } elseif (!in_array($unit, ['microseconds', 'milliseconds', 'seconds', 'minutes', 'hours', 'dayz', 'months', 'years'])) {
            throw new InvalidArgumentException("Unknown unit '$unit'.");
        }

        $result = 0;
        $cumulativeFactor = 0;
        $unitFound = false;
        $factors = static::getFlipCascadeFactors();

        foreach ($factors as $source => [$target, $factor]) {
            if ($source === $realUnit) {
                $unitFound = true;
                $value = $this->$source;
                if ($source === 'microseconds' && isset($factors['milliseconds'])) {
                    $value %= Carbon::MICROSECONDS_PER_MILLISECOND;
                }
                $result += $value;
                $cumulativeFactor = 1;
            }

            if ($factor === false) {
                if ($unitFound) {
                    break;
                }

                $result = 0;
                $cumulativeFactor = 0;

                continue;
            }

            if ($target === $realUnit) {
                $unitFound = true;
            }

            if ($cumulativeFactor) {
                $cumulativeFactor *= $factor;
                $result += $this->$target * $cumulativeFactor;

                continue;
            }

            $value = $this->$source;

            if ($source === 'microseconds' && isset($factors['milliseconds'])) {
                $value %= Carbon::MICROSECONDS_PER_MILLISECOND;
            }

            $result = ($result + $value) / $factor;
        }

        if (isset($target) && !$cumulativeFactor) {
            $result += $this->$target;
        }

        if (!$unitFound) {
            throw new \InvalidArgumentException("Unit $unit have no configuration to get total from other units.");
        }

        if ($unit === 'weeks') {
            return $result / static::getDaysPerWeek();
        }

        return $result;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 <?php

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
use Countable;
use DateInterval;
use DateTime;
use DateTimeInterface;
use InvalidArgumentException;
use Iterator;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;

/**
 * Substitution of DatePeriod with some modifications and many more features.
 *
 * @method static CarbonPeriod start($date, $inclusive = null) Create instance specifying start date.
 * @method static CarbonPeriod since($date, $inclusive = null) Alias for start().
 * @method static CarbonPeriod sinceNow($inclusive = null) Create instance with start date set to now.
 * @method static CarbonPeriod end($date = null, $inclusive = null) Create instance specifying end date.
 * @method static CarbonPeriod until($date = null, $inclusive = null) Alias for end().
 * @method static CarbonPeriod untilNow($inclusive = null) Create instance with end date set to now.
 * @method static CarbonPeriod dates($start, $end = null) Create instance with start and end date.
 * @method static CarbonPeriod between($start, $end = null) Create instance with start and end date.
 * @method static CarbonPeriod recurrences($recurrences = null) Create instance with maximum number of recurrences.
 * @method static CarbonPeriod times($recurrences = null) Alias for recurrences().
 * @method static CarbonPeriod options($options = null) Create instance with options.
 * @method static CarbonPeriod toggle($options, $state = null) Create instance with options toggled on or off.
 * @method static CarbonPeriod filter($callback, $name = null) Create instance with filter added to the stack.
 * @method static CarbonPeriod push($callback, $name = null) Alias for filter().
 * @method static CarbonPeriod prepend($callback, $name = null) Create instance with filter prepened to the stack.
 * @method static CarbonPeriod filters(array $filters) Create instance with filters stack.
 * @method static CarbonPeriod interval($interval) Create instance with given date interval.
 * @method static CarbonPeriod each($interval) Create instance with given date interval.
 * @method static CarbonPeriod every($interval) Create instance with given date interval.
 * @method static CarbonPeriod step($interval) Create instance with given date interval.
 * @method static CarbonPeriod stepBy($interval) Create instance with given date interval.
 * @method static CarbonPeriod invert() Create instance with inverted date interval.
 * @method static CarbonPeriod years($years = 1) Create instance specifying a number of years for date interval.
 * @method static CarbonPeriod year($years = 1) Alias for years().
 * @method static CarbonPeriod months($months = 1) Create instance specifying a number of months for date interval.
 * @method static CarbonPeriod month($months = 1) Alias for months().
 * @method static CarbonPeriod weeks($weeks = 1) Create instance specifying a number of weeks for date interval.
 * @method static CarbonPeriod week($weeks = 1) Alias for weeks().
 * @method static CarbonPeriod days($days = 1) Create instance specifying a number of days for date interval.
 * @method static CarbonPeriod dayz($days = 1) Alias for days().
 * @method static CarbonPeriod day($days = 1) Alias for days().
 * @method static CarbonPeriod hours($hours = 1) Create instance specifying a number of hours for date interval.
 * @method static CarbonPeriod hour($hours = 1) Alias for hours().
 * @method static CarbonPeriod minutes($minutes = 1) Create instance specifying a number of minutes for date interval.
 * @method static CarbonPeriod minute($minutes = 1) Alias for minutes().
 * @method static CarbonPeriod seconds($seconds = 1) Create instance specifying a number of seconds for date interval.
 * @method static CarbonPeriod second($seconds = 1) Alias for seconds().
 * @method CarbonPeriod start($date, $inclusive = null) Change the period start date.
 * @method CarbonPeriod since($date, $inclusive = null) Alias for start().
 * @method CarbonPeriod sinceNow($inclusive = null) Change the period start date to now.
 * @method CarbonPeriod end($date = null, $inclusive = null) Change the period end date.
 * @method CarbonPeriod until($date = null, $inclusive = null) Alias for end().
 * @method CarbonPeriod untilNow($inclusive = null) Change the period end date to now.
 * @method CarbonPeriod dates($start, $end = null) Change the period start and end date.
 * @method CarbonPeriod recurrences($recurrences = null) Change the maximum number of recurrences.
 * @method CarbonPeriod times($recurrences = null) Alias for recurrences().
 * @method CarbonPeriod options($options = null) Change the period options.
 * @method CarbonPeriod toggle($options, $state = null) Toggle given options on or off.
 * @method CarbonPeriod filter($callback, $name = null) Add a filter to the stack.
 * @method CarbonPeriod push($callback, $name = null) Alias for filter().
 * @method CarbonPeriod prepend($callback, $name = null) Prepend a filter to the stack.
 * @method CarbonPeriod filters(array $filters = []) Set filters stack.
 * @method CarbonPeriod interval($interval) Change the period date interval.
 * @method CarbonPeriod invert() Invert the period date interval.
 * @method CarbonPeriod years($years = 1) Set the years portion of the date interval.
 * @method CarbonPeriod year($years = 1) Alias for years().
 * @method CarbonPeriod months($months = 1) Set the months portion of the date interval.
 * @method CarbonPeriod month($months = 1) Alias for months().
 * @method CarbonPeriod weeks($weeks = 1) Set the weeks portion of the date interval.
 * @method CarbonPeriod week($weeks = 1) Alias for weeks().
 * @method CarbonPeriod days($days = 1) Set the days portion of the date interval.
 * @method CarbonPeriod dayz($days = 1) Alias for days().
 * @method CarbonPeriod day($days = 1) Alias for days().
 * @method CarbonPeriod hours($hours = 1) Set the hours portion of the date interval.
 * @method CarbonPeriod hour($hours = 1) Alias for hours().
 * @method CarbonPeriod minutes($minutes = 1) Set the minutes portion of the date interval.
 * @method CarbonPeriod minute($minutes = 1) Alias for minutes().
 * @method CarbonPeriod seconds($seconds = 1) Set the seconds portion of the date interval.
 * @method CarbonPeriod second($seconds = 1) Alias for seconds().
 */
class CarbonPeriod implements Iterator, Countable
{
    use Options;

    /**
     * Built-in filters.
     *
     * @var string
     */
    const RECURRENCES_FILTER = 'Carbon\CarbonPeriod::filterRecurrences';
    const END_DATE_FILTER = 'Carbon\CarbonPeriod::filterEndDate';

    /**
     * Special value which can be returned by filters to end iteration. Also a filter.
     *
     * @var string
     */
    const END_ITERATION = 'Carbon\CarbonPeriod::endIteration';

    /**
     * Available options.
     *
     * @var int
     */
    const EXCLUDE_START_DATE = 1;
    const EXCLUDE_END_DATE = 2;
    const IMMUTABLE = 4;

    /**
     * Number of maximum attempts before giving up on finding next valid date.
     *
     * @var int
     */
    const NEXT_MAX_ATTEMPTS = 1000;

    /**
     * The registered macros.
     *
     * @var array
     */
    protected static $macros = [];

    /**
     * Date class of iteration items.
     *
     * @var string
     */
    protected $dateClass = Carbon::class;

    /**
     * Underlying date interval instance. Always present, one day by default.
     *
     * @var CarbonInterval
     */
    protected $dateInterval;

    /**
     * Whether current date interval was set by default.
     *
     * @var bool
     */
    protected $isDefaultInterval;

    /**
     * The filters stack.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Period start date. Applied on rewind. Always present, now by default.
     *
     * @var CarbonInterface
     */
    protected $startDate;

    /**
     * Period end date. For inverted interval should be before the start date. Applied via a filter.
     *
     * @var CarbonInterface|null
     */
    protected $endDate;

    /**
     * Limit for number of recurrences. Applied via a filter.
     *
     * @var int|null
     */
    protected $recurrences;

    /**
     * Iteration options.
     *
     * @var int
     */
    protected $options;

    /**
     * Index of current date. Always sequential, even if some dates are skipped by filters.
     * Equal to null only before the first iteration.
     *
     * @var int
     */
    protected $key;

    /**
     * Current date. May temporarily hold unaccepted value when looking for a next valid date.
     * Equal to null only before the first iteration.
     *
     * @var CarbonInterface
     */
    protected $current;

    /**
     * Timezone of current date. Taken from the start date.
     *
     * @var \DateTimeZone|null
     */
    protected $timezone;

    /**
     * The cached validation result for current date.
     *
     * @var bool|string|null
     */
    protected $validationResult;

    /**
     * Timezone handler for settings() method.
     *
     * @var mixed
     */
    protected $tzName;

    /**
     * Create a new instance.
     *
     * @return static
     */
    public static function create(...$params)
    {
        return static::createFromArray($params);
    }

    /**
     * Create a new instance from an array of parameters.
     *
     * @param array $params
     *
     * @return static
     */
    public static function createFromArray(array $params)
    {
        return new static(...$params);
    }

    /**
     * Create CarbonPeriod from ISO 8601 string.
     *
     * @param string   $iso
     * @param int|null $options
     *
     * @return static
     */
    public static function createFromIso($iso, $options = null)
    {
        $params = static::parseIso8601($iso);

        $instance = static::createFromArray($params);

        if ($options !== null) {
            $instance->setOptions($options);
        }

        return $instance;
    }

    /**
     * Return whether given interval contains non zero value of any time unit.
     *
     * @param \DateInterval $interval
     *
     * @return bool
     */
    protected static function intervalHasTime(DateInterval $interval)
    {
        // The array_key_exists and get_object_vars are used as a workaround to check microsecond support.
        // Both isset and property_exists will fail on PHP 7.0.14 - 7.0.21 due to the following bug:
        // https://bugs.php.net/bug.php?id=74852
        return $interval->h || $interval->i || $interval->s || array_key_exists('f', get_object_vars($interval)) && $interval->f;
    }

    /**
     * Return whether given variable is an ISO 8601 specification.
     *
     * Note: Check is very basic, as actual validation will be done later when parsing.
     * We just want to ensure that variable is not any other type of a valid parameter.
     *
     * @param mixed $var
     *
     * @return bool
     */
    protected static function isIso8601($var)
    {
        if (!is_string($var)) {
            return false;
        }

        // Match slash but not within a timezone name.
        $part = '[a-z]+(?:[_-][a-z]+)*';

        preg_match("#\b$part/$part\b|(/)#i", $var, $match);

        return isset($match[1]);
    }

    /**
     * Parse given ISO 8601 string into an array of arguments.
     *
     * @param string $iso
     *
     * @return array
     */
    protected static function parseIso8601($iso)
    {
        $result = [];

        $interval = null;
        $start = null;
        $end = null;

        foreach (explode('/', $iso) as $key => $part) {
            if ($key === 0 && preg_match('/^R([0-9]*)$/', $part, $match)) {
                $parsed = strlen($match[1]) ? (int) $match[1] : null;
            } elseif ($interval === null && $parsed = CarbonInterval::make($part)) {
                $interval = $part;
            } elseif ($start === null && $parsed = Carbon::make($part)) {
                $start = $part;
            } elseif ($end === null && $parsed = Carbon::make(static::addMissingParts($start, $part))) {
                $end = $part;
            } else {
                throw new InvalidArgumentException("Invalid ISO 8601 specification: $iso.");
            }

            $result[] = $parsed;
        }

        return $result;
    }

    /**
     * Add missing parts of the target date from the soure date.
     *
     * @param string $source
     * @param string $target
     *
     * @return string
     */
    protected static function addMissingParts($source, $target)
    {
        $pattern = '/'.preg_replace('/[0-9]+/', '[0-9]+', preg_quote($target, '/')).'$/';

        $result = preg_replace($pattern, $target, $source, 1, $count);

        return $count ? $result : $target;
    }

    /**
     * Register a custom macro.
     *
     * @example
     * ```
     * CarbonPeriod::macro('middle', function () {
     *   return $this->getStartDate()->average($this->getEndDate());
     * });
     * echo CarbonPeriod::since('2011-05-12')->until('2011-06-03')->middle();
     * ```
     *
     * @param string          $name
     * @param object|callable $macro
     *
     * @return void
     */
    public static function macro($name, $macro)
    {
        static::$macros[$name] = $macro;
    }

    /**
     * Register macros from a mixin object.
     *
     * @example
     * ```
     * CarbonPeriod::mixin(new class {
     *   public function addDays() {
     *     return function ($count = 1) {
     *       return $this->setStartDate(
     *         $this->getStartDate()->addDays($count)
     *       )->setEndDate(
     *         $this->getEndDate()->addDays($count)
     *       );
     *     };
     *   }
     *   public function subDays() {
     *     return function ($count = 1) {
     *       return $this->setStartDate(
     *         $this->getStartDate()->subDays($count)
     *       )->setEndDate(
     *         $this->getEndDate()->subDays($count)
     *       );
     *     };
     *   }
     * });
     * echo CarbonPeriod::create('2000-01-01', '2000-02-01')->addDays(5)->subDays(3);
     * ```
     *
     * @param object $mixin
     *
     * @throws \ReflectionException
     *
     * @return void
     */
    public static function mixin($mixin)
    {
        $reflection = new ReflectionClass($mixin);

        $methods = $reflection->getMethods(
            ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED
        );

        foreach ($methods as $method) {
            $method->setAccessible(true);

            static::macro($method->name, $method->invoke($mixin));
        }
    }

    /**
     * Check if macro is registered.
     *
     * @param string $name
     *
     * @return bool
     */
    public static function hasMacro($name)
    {
        return isset(static::$macros[$name]);
    }

    /**
     * Provide static proxy for instance aliases.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    /**
     * CarbonPeriod constructor.
     *
     * @throws InvalidArgumentException
     */
    public function __construct(...$arguments)
    {
        // Parse and assign arguments one by one. First argument may be an ISO 8601 spec,
        // which will be first parsed into parts and then processed the same way.

        if (count($arguments) && static::isIso8601($iso = $arguments[0])) {
            array_splice($arguments, 0, 1, static::parseIso8601($iso));
        }

        foreach ($arguments as $argument) {
            if ($this->dateInterval === null &&
                (
                    is_string($argument) && preg_match('/^(\d.*|P[T0-9].*|(?:\h*\d+(?:\.\d+)?\h*[a-z]+)+)$/i', $argument) ||
                    $argument instanceof DateInterval
                ) &&
                $parsed = CarbonInterval::make($argument)
            ) {
                $this->setDateInterval($parsed);
            } elseif ($this->startDate === null && $parsed = Carbon::make($argument)) {
                $this->setStartDate($parsed);
            } elseif ($this->endDate === null && $parsed = Carbon::make($argument)) {
                $this->setEndDate($parsed);
            } elseif ($this->recurrences === null && $this->endDate === null && is_numeric($argument)) {
                $this->setRecurrences($argument);
            } elseif ($this->options === null && (is_int($argument) || $argument === null)) {
                $this->setOptions($argument);
            } else {
                throw new InvalidArgumentException('Invalid constructor parameters.');
            }
        }

        if ($this->startDate === null) {
            $this->setStartDate(Carbon::now());
        }

        if ($this->dateInterval === null) {
            $this->setDateInterval(CarbonInterval::day());

            $this->isDefaultInterval = true;
        }

        if ($this->options === null) {
            $this->setOptions(0);
        }
    }

    /**
     * Return whether given callable is a string pointing to one of Carbon's is* methods
     * and should be automatically converted to a filter callback.
     *
     * @param callable $callable
     *
     * @return bool
     */
    protected function isCarbonPredicateMethod($callable)
    {
        return is_string($callable) && substr($callable, 0, 2) === 'is' && (method_exists($this->dateClass, $callable) || call_user_func([$this->dateClass, 'hasMacro'], $callable));
    }

    /**
     * Set the iteration item class.
     *
     * @param string $dateClass
     *
     * @return $this
     */
    public function setDateClass(string $dateClass)
    {
        if (!is_a($dateClass, CarbonInterface::class, true)) {
            throw new InvalidArgumentException(sprintf(
                'Given class does not implement %s: %s', CarbonInterface::class, $dateClass
            ));
        }

        $this->dateClass = $dateClass;

        if (is_a($dateClass, Carbon::class, true)) {
            $this->toggleOptions(static::IMMUTABLE, false);
        } elseif (is_a($dateClass, CarbonImmutable::class, true)) {
            $this->toggleOptions(static::IMMUTABLE, true);
        }

        return $this;
    }

    /**
     * Returns iteration item date class.
     *
     * @return string
     */
    public function getDateClass(): string
    {
        return $this->dateClass;
    }

    /**
     * Change the period date interval.
     *
     * @param DateInterval|string $interval
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setDateInterval($interval)
    {
        if (!$interval = CarbonInterval::make($interval)) {
            throw new InvalidArgumentException('Invalid interval.');
        }

        if ($interval->spec() === 'PT0S') {
            throw new InvalidArgumentException('Empty interval is not accepted.');
        }

        $this->dateInterval = $interval;

        $this->isDefaultInterval = false;

        $this->handleChangedParameters();

        return $this;
    }

    /**
     * Invert the period date interval.
     *
     * @return $this
     */
    public function invertDateInterval()
    {
        $interval = $this->dateInterval->invert();

        return $this->setDateInterval($interval);
    }

    /**
     * Set start and end date.
     *
     * @param DateTime|DateTimeInterface|string      $start
     * @param DateTime|DateTimeInterface|string|null $end
     *
     * @return $this
     */
    public function setDates($start, $end)
    {
        $this->setStartDate($start);
        $this->setEndDate($end);

        return $this;
    }

    /**
     * Change the period options.
     *
     * @param int|null $options
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setOptions($options)
    {
        if (!is_int($options) && !is_null($options)) {
            throw new InvalidArgumentException('Invalid options.');
        }

        $this->options = $options ?: 0;

        $this->handleChangedParameters();

        return $this;
    }

    /**
     * Get the period options.
     *
     * @return int
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Toggle given options on or off.
     *
     * @param int       $options
     * @param bool|null $state
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function toggleOptions($options, $state = null)
    {
        if ($state === null) {
            $state = ($this->options & $options) !== $options;
        }

        return $this->setOptions(
            $state ?
            $this->options | $options :
            $this->options & ~$options
        );
    }

    /**
     * Toggle EXCLUDE_START_DATE option.
     *
     * @param bool $state
     *
     * @return $this
     */
    public function excludeStartDate($state = true)
    {
        return $this->toggleOptions(static::EXCLUDE_START_DATE, $state);
    }

    /**
     * Toggle EXCLUDE_END_DATE option.
     *
     * @param bool $state
     *
     * @return $this
     */
    public function excludeEndDate($state = true)
    {
        return $this->toggleOptions(static::EXCLUDE_END_DATE, $state);
    }

    /**
     * Get the underlying date interval.
     *
     * @return CarbonInterval
     */
    public function getDateInterval()
    {
        return $this->dateInterval->copy();
    }

    /**
     * Get start date of the period.
     *
     * @return CarbonInterface
     */
    public function getStartDate()
    {
        return $this->startDate->copy();
    }

    /**
     * Get end date of the period.
     *
     * @return CarbonInterface|null
     */
    public function getEndDate()
    {
        return $this->endDate ? $this->endDate->copy() : null;
    }

    /**
     * Get number of recurrences.
     *
     * @return int|null
     */
    public function getRecurrences()
    {
        return $this->recurrences;
    }

    /**
     * Returns true if the start date should be excluded.
     *
     * @return bool
     */
    public function isStartExcluded()
    {
        return ($this->options & static::EXCLUDE_START_DATE) !== 0;
    }

    /**
     * Returns true if the end date should be excluded.
     *
     * @return bool
     */
    public function isEndExcluded()
    {
        return ($this->options & static::EXCLUDE_END_DATE) !== 0;
    }

    /**
     * Add a filter to the stack.
     *
     * @param callable $callback
     * @param string   $name
     *
     * @return $this
     */
    public function addFilter($callback, $name = null)
    {
        $tuple = $this->createFilterTuple(func_get_args());

        $this->filters[] = $tuple;

        $this->handleChangedParameters();

        return $this;
    }

    /**
     * Prepend a filter to the stack.
     *
     * @param callable $callback
     * @param string   $name
     *
     * @return $this
     */
    public function prependFilter($callback, $name = null)
    {
        $tuple = $this->createFilterTuple(func_get_args());

        array_unshift($this->filters, $tuple);

        $this->handleChangedParameters();

        return $this;
    }

    /**
     * Create a filter tuple from raw parameters.
     *
     * Will create an automatic filter callback for one of Carbon's is* methods.
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function createFilterTuple(array $parameters)
    {
        $method = array_shift($parameters);

        if (!$this->isCarbonPredicateMethod($method)) {
            return [$method, array_shift($parameters)];
        }

        return [function ($date) use ($method, $parameters) {
            return call_user_func_array([$date, $method], $parameters);
        }, $method];
    }

    /**
     * Remove a filter by instance or name.
     *
     * @param callable|string $filter
     *
     * @return $this
     */
    public function removeFilter($filter)
    {
        $key = is_callable($filter) ? 0 : 1;

        $this->filters = array_values(array_filter(
            $this->filters,
            function ($tuple) use ($key, $filter) {
                return $tuple[$key] !== $filter;
            }
        ));

        $this->updateInternalState();

        $this->handleChangedParameters();

        return $this;
    }

    /**
     * Return whether given instance or name is in the filter stack.
     *
     * @param callable|string $filter
     *
     * @return bool
     */
    public function hasFilter($filter)
    {
        $key = is_callable($filter) ? 0 : 1;

        foreach ($this->filters as $tuple) {
            if ($tuple[$key] === $filter) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get filters stack.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Set filters stack.
     *
     * @param array $filters
     *
     * @return $this
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        $this->updateInternalState();

        $this->handleChangedParameters();

        return $this;
    }

    /**
     * Reset filters stack.
     *
     * @return $this
     */
    public function resetFilters()
    {
        $this->filters = [];

        if ($this->endDate !== null) {
            $this->filters[] = [static::END_DATE_FILTER, null];
        }

        if ($this->recurrences !== null) {
            $this->filters[] = [static::RECURRENCES_FILTER, null];
        }

        $this->handleChangedParameters();

        return $this;
    }

    /**
     * Update properties after removing built-in filters.
     *
     * @return void
     */
    protected function updateInternalState()
    {
        if (!$this->hasFilter(static::END_DATE_FILTER)) {
            $this->endDate = null;
        }

        if (!$this->hasFilter(static::RECURRENCES_FILTER)) {
            $this->recurrences = null;
        }
    }

    /**
     * Add a recurrences filter (set maximum number of recurrences).
     *
     * @param int|null $recurrences
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setRecurrences($recurrences)
    {
        if (!is_numeric($recurrences) && !is_null($recurrences) || $recurrences < 0) {
            throw new InvalidArgumentException('Invalid number of recurrences.');
        }

        if ($recurrences === null) {
            return $this->removeFilter(static::RECURRENCES_FILTER);
        }

        $this->recurrences = (int) $recurrences;

        if (!$this->hasFilter(static::RECURRENCES_FILTER)) {
            return $this->addFilter(static::RECURRENCES_FILTER);
        }

        $this->handleChangedParameters();

        return $this;
    }

    /**
     * Recurrences filter callback (limits number of recurrences).
     *
     * @param \Carbon\Carbon $current
     * @param int            $key
     *
     * @return bool|string
     */
    protected function filterRecurrences($current, $key)
    {
        if ($key < $this->recurrences) {
            return true;
        }

        return static::END_ITERATION;
    }

    /**
     * Change the period start date.
     *
     * @param DateTime|DateTimeInterface|string $date
     * @param bool|null                         $inclusive
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setStartDate($date, $inclusive = null)
    {
        if (!$date = call_user_func([$this->dateClass, 'make'], $date)) {
            throw new InvalidArgumentException('Invalid start date.');
        }

        $this->startDate = $date;

        if ($inclusive !== null) {
            $this->toggleOptions(static::EXCLUDE_START_DATE, !$inclusive);
        }

        return $this;
    }

    /**
     * Change the period end date.
     *
     * @param DateTime|DateTimeInterface|string|null $date
     * @param bool|null                              $inclusive
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setEndDate($date, $inclusive = null)
    {
        if (!is_null($date) && !$date = call_user_func([$this->dateClass, 'make'], $date)) {
            throw new InvalidArgumentException('Invalid end date.');
        }

        if (!$date) {
            return $this->removeFilter(static::END_DATE_FILTER);
        }

        $this->endDate = $date;

        if ($inclusive !== null) {
            $this->toggleOptions(static::EXCLUDE_END_DATE, !$inclusive);
        }

        if (!$this->hasFilter(static::END_DATE_FILTER)) {
            return $this->addFilter(static::END_DATE_FILTER);
        }

        $this->handleChangedParameters();

        return $this;
    }

    /**
     * End date filter callback.
     *
     * @param \Carbon\Carbon $current
     *
     * @return bool|string
     */
    protected function filterEndDate($current)
    {
        if (!$this->isEndExcluded() && $current == $this->endDate) {
            return true;
        }

        if ($this->dateInterval->invert ? $current > $this->endDate : $current < $this->endDate) {
            return true;
        }

        return static::END_ITERATION;
    }

    /**
     * End iteration filter callback.
     *
     * @return string
     */
    protected function endIteration()
    {
        return static::END_ITERATION;
    }

    /**
     * Handle change of the parameters.
     */
    protected function handleChangedParameters()
    {
        if (($this->getOptions() & static::IMMUTABLE) && $this->dateClass === Carbon::class) {
            $this->setDateClass(CarbonImmutable::class);
        } elseif (!($this->getOptions() & static::IMMUTABLE) && $this->dateClass === CarbonImmutable::class) {
            $this->setDateClass(Carbon::class);
        }

        $this->validationResult = null;
    }

    /**
     * Validate current date and stop iteration when necessary.
     *
     * Returns true when current date is valid, false if it is not, or static::END_ITERATION
     * when iteration should be stopped.
     *
     * @return bool|string
     */
    protected function validateCurrentDate()
    {
        if ($this->current === null) {
            $this->rewind();
        }

        // Check after the first rewind to avoid repeating the initial validation.
        if ($this->validationResult !== null) {
            return $this->validationResult;
        }

        return $this->validationResult = $this->checkFilters();
    }

    /**
     * Check whether current value and key pass all the filters.
     *
     * @return bool|string
     */
    protected function checkFilters()
    {
        $current = $this->prepareForReturn($this->current);

        foreach ($this->filters as $tuple) {
            $result = call_user_func(
                $tuple[0],
                $current->copy(),
                $this->key,
                $this
            );

            if ($result === static::END_ITERATION) {
                return static::END_ITERATION;
            }

            if (!$result) {
                return false;
            }
        }

        return true;
    }

    /**
     * Prepare given date to be returned to the external logic.
     *
     * @param CarbonInterface $date
     *
     * @return Carbon
     */
    protected function prepareForReturn(CarbonInterface $date)
    {
        $date = call_user_func([$this->dateClass, 'make'], $date);

        if ($this->timezone) {
            $date = $date->setTimezone($this->timezone);
        }

        return $date;
    }

    /**
     * Check if the current position is valid.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->validateCurrentDate() === true;
    }

    /**
     * Return the current key.
     *
     * @return int|null
     */
    public function key()
    {
        if ($this->valid()) {
            return $this->key;
        }
    }

    /**
     * Return the current date.
     *
     * @return Carbon|null
     */
    public function current()
    {
        if ($this->valid()) {
            return $this->prepareForReturn($this->current);
        }
    }

    /**
     * Move forward to the next date.
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function next()
    {
        if ($this->current === null) {
            $this->rewind();
        }

        if ($this->validationResult !== static::END_ITERATION) {
            $this->key++;

            $this->incrementCurrentDateUntilValid();
        }
    }

    /**
     * Rewind to the start date.
     *
     * Iterating over a date in the UTC timezone avoids bug during backward DST change.
     *
     * @see https://bugs.php.net/bug.php?id=72255
     * @see https://bugs.php.net/bug.php?id=74274
     * @see https://wiki.php.net/rfc/datetime_and_daylight_saving_time
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function rewind()
    {
        $this->key = 0;
        $this->current = call_user_func([$this->dateClass, 'make'], $this->startDate);
        $settings = $this->getSettings();
        $locale = $this->getLocalTranslator()->getLocale();
        if ($locale) {
            $settings['locale'] = $locale;
        }
        $this->current->settings($settings);
        $this->timezone = static::intervalHasTime($this->dateInterval) ? $this->current->getTimezone() : null;

        if ($this->timezone) {
            $this->current = $this->current->utc();
        }

        $this->validationResult = null;

        if ($this->isStartExcluded() || $this->validateCurrentDate() === false) {
            $this->incrementCurrentDateUntilValid();
        }
    }

    /**
     * Skip iterations and returns iteration state (false if ended, true if still valid).
     *
     * @param int $count steps number to skip (1 by default)
     *
     * @return bool
     */
    public function skip($count = 1)
    {
        for ($i = $count; $this->valid() && $i > 0; $i--) {
            $this->next();
        }

        return $this->valid();
    }

    /**
     * Keep incrementing the current date until a valid date is found or the iteration is ended.
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function incrementCurrentDateUntilValid()
    {
        $attempts = 0;

        do {
            $this->current = $this->current->add($this->dateInterval);

            $this->validationResult = null;

            if (++$attempts > static::NEXT_MAX_ATTEMPTS) {
                throw new RuntimeException('Could not find next valid date.');
            }
        } while ($this->validateCurrentDate() === false);
    }

    /**
     * Format the date period as ISO 8601.
     *
     * @return string
     */
    public function toIso8601String()
    {
        $parts = [];

        if ($this->recurrences !== null) {
            $parts[] = 'R'.$this->recurrences;
        }

        $parts[] = $this->startDate->toIso8601String();

        $parts[] = $this->dateInterval->spec();

        if ($this->endDate !== null) {
            $parts[] = $this->endDate->toIso8601String();
        }

        return implode('/', $parts);
    }

    /**
     * Convert the date period into a string.
     *
     * @return string
     */
    public function toString()
    {
        $translator = call_user_func([$this->dateClass, 'getTranslator']);

        $parts = [];

        $format = !$this->startDate->isStartOfDay() || $this->endDate && !$this->endDate->isStartOfDay()
            ? 'Y-m-d H:i:s'
            : 'Y-m-d';

        if ($this->recurrences !== null) {
            $parts[] = $this->translate('period_recurrences', [], $this->recurrences, $translator);
        }

        $parts[] = $this->translate('period_interval', [':interval' => $this->dateInterval->forHumans([
            'join' => true,
        ])], null, $translator);

        $parts[] = $this->translate('period_start_date', [':date' => $this->startDate->rawFormat($format)], null, $translator);

        if ($this->endDate !== null) {
            $parts[] = $this->translate('period_end_date', [':date' => $this->endDate->rawFormat($format)], null, $translator);
        }

        $result = implode(' ', $parts);

        return mb_strtoupper(mb_substr($result, 0, 1)).mb_substr($result, 1);
    }

    /**
     * Format the date period as ISO 8601.
     *
     * @return string
     */
    public function spec()
    {
        return $this->toIso8601String();
    }

    /**
     * Convert the date period into an array without changing current iteration state.
     *
     * @return array
     */
    public function toArray()
    {
        $state = [
            $this->key,
            $this->current ? $this->current->copy() : null,
            $this->validationResult,
        ];

        $result = iterator_to_array($this);

        [
            $this->key,
            $this->current,
            $this->validationResult
        ] = $state;

        return $result;
    }

    /**
     * Count dates in the date period.
     *
     * @return int
     */
    public function count()
    {
        return count($this->toArray());
    }

    /**
     * Return the first date in the date period.
     *
     * @return Carbon|null
     */
    public function first()
    {
        if ($array = $this->toArray()) {
            return $array[0];
        }
    }

    /**
     * Return the last date in the date period.
     *
     * @return Carbon|null
     */
    public function last()
    {
        if ($array = $this->toArray()) {
            return $array[count($array) - 1];
        }
    }

    /**
     * Call given macro.
     *
     * @param string $name
     * @param array  $parameters
     *
     * @return mixed
     */
    protected function callMacro($name, $parameters)
    {
        $macro = static::$macros[$name];

        if ($macro instanceof Closure) {
            return call_user_func_array($macro->bindTo($this, static::class), $parameters);
        }

        return call_user_func_array($macro, $parameters);
    }

    /**
     * Convert the date period into a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Add aliases for setters.
     *
     * CarbonPeriod::days(3)->hours(5)->invert()
     *     ->sinceNow()->until('2010-01-10')
     *     ->filter(...)
     *     ->count()
     *
     * Note: We use magic method to let static and instance aliases with the same names.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->callMacro($method, $parameters);
        }

        $first = count($parameters) >= 1 ? $parameters[0] : null;
        $second = count($parameters) >= 2 ? $parameters[1] : null;

        switch ($method) {
            case 'start':
            case 'since':
                return $this->setStartDate($first, $second);

            case 'sinceNow':
                return $this->setStartDate(new Carbon, $first);

            case 'end':
            case 'until':
                return $this->setEndDate($first, $second);

            case 'untilNow':
                return $this->setEndDate(new Carbon, $first);

            case 'dates':
            case 'between':
                return $this->setDates($first, $second);

            case 'recurrences':
            case 'times':
                return $this->setRecurrences($first);

            case 'options':
                return $this->setOptions($first);

            case 'toggle':
                return $this->toggleOptions($first, $second);

            case 'filter':
            case 'push':
                return $this->addFilter($first, $second);

            case 'prepend':
                return $this->prependFilter($first, $second);

            case 'filters':
                return $this->setFilters($first ?: []);

            case 'interval':
            case 'each':
            case 'every':
            case 'step':
            case 'stepBy':
                return $this->setDateInterval($first);

            case 'invert':
                return $this->invertDateInterval();

            case 'years':
            case 'year':
            case 'months':
            case 'month':
            case 'weeks':
            case 'week':
            case 'days':
            case 'dayz':
            case 'day':
            case 'hours':
            case 'hour':
            case 'minutes':
            case 'minute':
            case 'seconds':
            case 'second':
                return $this->setDateInterval(call_user_func(
                    // Override default P1D when instantiating via fluent setters.
                    [$this->isDefaultInterval ? new CarbonInterval('PT0S') : $this->dateInterval, $method],
                    count($parameters) === 0 ? 1 : $first
                ));
        }

        if ($this->localStrictModeEnabled ?? Carbon::isStrictModeEnabled()) {
            throw new BadMethodCallException("Method $method does not exist.");
        }

        return $this;
    }

    public function shiftTimezone($timezone)
    {
        $this->tzName = $timezone;
        $this->timezone = $timezone;

        return $this;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon;

use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

class CarbonTimeZone extends DateTimeZone
{
    public function __construct($timezone = null)
    {
        parent::__construct(static::getDateTimeZoneNameFromMixed($timezone));
    }

    protected static function getDateTimeZoneNameFromMixed($timezone)
    {
        if (is_null($timezone)) {
            $timezone = date_default_timezone_get();
        } elseif (is_numeric($timezone)) {
            if ($timezone <= -100 || $timezone >= 100) {
                throw new InvalidArgumentException('Absolute timezone offset cannot be greater than 100.');
            }

            $timezone = ($timezone >= 0 ? '+' : '').$timezone.':00';
        }

        return $timezone;
    }

    protected static function getDateTimeZoneFromName(&$name)
    {
        return @timezone_open($name = (string) static::getDateTimeZoneNameFromMixed($name));
    }

    /**
     * Create a CarbonTimeZone from mixed input.
     *
     * @param DateTimeZone|string|int|null $object     original value to get CarbonTimeZone from it.
     * @param DateTimeZone|string|int|null $objectDump dump of the object for error messages.
     *
     * @return false|static
     */
    public static function instance($object = null, $objectDump = null)
    {
        $tz = $object;

        if ($tz instanceof static) {
            return $tz;
        }

        if ($tz === null) {
            return new static();
        }

        if (!$tz instanceof DateTimeZone) {
            $tz = static::getDateTimeZoneFromName($object);
        }

        if ($tz === false) {
            if (Carbon::isStrictModeEnabled()) {
                throw new InvalidArgumentException('Unknown or bad timezone ('.($objectDump ?: $object).')');
            }

            return false;
        }

        return new static($tz->getName());
    }

    /**
     * Returns abbreviated name of the current timezone according to DST setting.
     *
     * @param bool $dst
     *
     * @return string
     */
    public function getAbbreviatedName($dst = false)
    {
        $name = $this->getName();

        foreach ($this->listAbbreviations() as $abbreviation => $zones) {
            foreach ($zones as $zone) {
                if ($zone['timezone_id'] === $name && $zone['dst'] == $dst) {
                    return $abbreviation;
                }
            }
        }

        return 'unknown';
    }

    /**
     * @alias getAbbreviatedName
     *
     * Returns abbreviated name of the current timezone according to DST setting.
     *
     * @param bool $dst
     *
     * @return string
     */
    public function getAbbr($dst = false)
    {
        return $this->getAbbreviatedName($dst);
    }

    /**
     * Get the offset as string "sHH:MM" (such as "+00:00" or "-12:30").
     *
     * @param DateTimeInterface|null $date
     *
     * @return string
     */
    public function toOffsetName(DateTimeInterface $date = null)
    {
        $minutes = floor($this->getOffset($date ?: Carbon::now($this)) / 60);

        $hours = floor($minutes / 60);

        $minutes = str_pad(abs($minutes) % 60, 2, '0', STR_PAD_LEFT);

        return ($hours < 0 ? '-' : '+').str_pad(abs($hours), 2, '0', STR_PAD_LEFT).":$minutes";
    }

    /**
     * Returns a new CarbonTimeZone object using the offset string instead of region string.
     *
     * @param DateTimeInterface|null $date
     *
     * @return CarbonTimeZone
     */
    public function toOffsetTimeZone(DateTimeInterface $date = null)
    {
        return new static($this->toOffsetName($date));
    }

    /**
     * Returns the first region string (such as "America/Toronto") that matches the current timezone.
     *
     * @see timezone_name_from_abbr native PHP function.
     *
     * @param DateTimeInterface|null $date
     * @param int                    $isDst
     *
     * @return string
     */
    public function toRegionName(DateTimeInterface $date = null, $isDst = 1)
    {
        $name = $this->getName();
        $firstChar = substr($name, 0, 1);

        if ($firstChar !== '+' && $firstChar !== '-') {
            return $name;
        }

        return @timezone_name_from_abbr(null, $this->getOffset($date ?: Carbon::now($this)), $isDst);
    }

    /**
     * Returns a new CarbonTimeZone object using the region string instead of offset string.
     *
     * @param DateTimeInterface|null $date
     *
     * @return CarbonTimeZone|false
     */
    public function toRegionTimeZone(DateTimeInterface $date = null)
    {
        $tz = $this->toRegionName($date);

        if ($tz === false) {
            if (Carbon::isStrictModeEnabled()) {
                throw new InvalidArgumentException('Unknown timezone for offset '.$this->getOffset($date ?: Carbon::now($this)).' seconds.');
            }

            return false;
        }

        return new static($tz);
    }

    /**
     * Cast to string (get timezone name).
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Create a CarbonTimeZone from mixed input.
     *
     * @param DateTimeZone|string|int|null $object
     *
     * @return false|static
     */
    public static function create($object = null)
    {
        return static::instance($object);
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               INDX( 	 ˝ÆÖ             (   ®  Ë       r ’’                Õ     h V     Ã     õ@4pk’ 1®¥f˝‘\ô‹<’õ@4pk’ `     ÊT             
 C a r b o n . p h p   Œ     x h     Ã     Ô¢	4pk’ 1®¥f˝‘LΩô‹<’Ô¢	4pk’ `     ¡T              C a r b o n I m m u t a b l e . p h p œ     x h     Ã     ›4pk’ 1®¥f˝‘´ ô‹<’›4pk’ @     ˘;              C a r b o n I n t e r f a c e . p h p –     x f     Ã     n,4pk’ 1®¥f˝‘´ ô‹<’n,4pk’ ¿      oª               C a r b o n I n t e  v a l . p h p   —     x b     Ã     §‹B4pk’ 1®¥f˝‘Éô‹<’§‹B4pk’ ∞      ¡£               C a r b o n P e r i o d . p h p       “     x f     Ã     >E4pk’ 1®¥f˝‘]‰ô‹<’>E4pk’        —               C a r b o n T i m e Z o n e . p h p   ◊     h V     Ã     ŸMµ4pk’=÷æ4pk’=÷æ4pk’ŸMµ4pk’                       
 E x c e p t i o n s p ”     h X     Ã     $çS4pk’ 1®¥f˝‘¡Gô‹<’$çS4pk’ –      6¬               F a c t o r y . p h p ‘     Ä j     Ã     àv~4pk’ 1®¥f˝‘¡Gô‹< àv~4pk’ ¿      'Ω               F a c t o r y I m m u t a b l e . p h p       Ÿ     ` J     Ã     =÷æ4pk’4M@Jpk’4M@Jpk’=÷æ4pk’                        L a n g u a g ’     p Z     Ã     ÖÎ≤4pk’ 1®¥f˝‘™ô‹<’ÖÎ≤4pk’        b               L a n g u a g e . p h p            ` P     Ã     ~ØBJpk’~ØBJpk’~ØBJpk’~ØBJpk’                        L a r a v e l      ` J     Ã     ˚EJpk’•˝PJpk’•˝PJpk’˚EJpk’                        L i s t s l a      ` N     Ã     `SJpk '¡tJpk’'¡tJpk’`SJpk’                        T r a i t s a ÷     p ^     Ã     ÖÎ≤4pk’ 1®¥f˝‘ß ô‹<’ÖÎ≤4pk’ 0      t'               T r a n s l a t o r . p h p                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon;

/**
 * A factory to generate Carbon instances with common settings.
 *
 * <autodoc generated by `composer phpdoc`>
 *
 * @method Carbon                                             create($year = 0, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0, $tz = null)                                           Create a new Carbon instance from a specific date and time.
 *                                                                                                                                                                                               If any of $year, $month or $day are set to null their now() values will
 *                                                                                                                                                                                               be used.
 *                                                                                                                                                                                               If $hour is null it will be set to its now() value and the default
 *                                                                                                                                                                                               values for $minute and $second will be their now() values.
 *                                                                                                                                                                                               If $hour is not null then the default values for $minute and $second
 *                                                                                                                                                                                               will be 0.
 * @method Carbon                                             createFromDate($year = null, $month = null, $day = null, $tz = null)                                                               Create a Carbon instance from just a date. The time portion is set to now.
 * @method Carbon|false                                       createFromFormat($format, $time, $tz = null)                                                                                       Create a Carbon instance from a specific format.
 * @method Carbon|false                                       createFromIsoFormat($format, $time, $tz = null, $locale = 'en', $translator = null)                                                Create a Carbon instance from a specific ISO format (same replacements as ->isoFormat()).
 * @method Carbon|false                                       createFromLocaleFormat($format, $locale, $time, $tz = null)                                                                        Create a Carbon instance from a specific format and a string in a given language.
 * @method Carbon|false                                       createFromLocaleIsoFormat($format, $locale, $time, $tz = null)                                                                     Create a Carbon instance from a specific ISO format and a string in a given language.
 * @method Carbon                                             createFromTime($hour = 0, $minute = 0, $second = 0, $tz = null)                                                                    Create a Carbon instance from just a time. The date portion is set to today.
 * @method Carbon                                             createFromTimeString($time, $tz = null)                                                                                            Create a Carbon instance from a time string. The date portion is set to today.
 * @method Carbon                                             createFromTimestamp($timestamp, $tz = null)                                                                                        Create a Carbon instance from a timestamp.
 * @method Carbon                                             createFromTimestampMs($timestamp, $tz = null)                                                                                      Create a Carbon instance from a timestamp in milliseconds.
 * @method Carbon                                             createFromTimestampUTC($timestamp)                                                                                                 Create a Carbon instance from an UTC timestamp.
 * @method Carbon                                             createMidnightDate($year = null, $month = null, $day = null, $tz = null)                                                           Create a Carbon instance from just a date. The time portion is set to midnight.
 * @method Carbon|false                                       createSafe($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $tz = null)                     Create a new safe Carbon instance from a specific date and time.
 *                                                                                                                                                                                               If any of $year, $month or $day are set to null their now() values will
 *                                                                                                                                                                                               be used.
 *                                                                                                                                                                                               If $hour is null it will be set to its now() value and the default
 *                                                                                                                                                                                               values for $minute and $second will be their now() values.
 *                                                                                                                                                                                               If $hour is not null then the default values for $minute and $second
 *                                                                                                                                                                                               will be 0.
 *                                                                                                                                                                                               If one of the set values is not valid, an \InvalidArgumentException
 *                                                                                                                                                                                               will be thrown.
 * @method Carbon                                             disableHumanDiffOption($humanDiffOption)                                                                                           @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 * @method Carbon                                             enableHumanDiffOption($humanDiffOption)                                                                                            @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 * @method mixed                                              executeWithLocale($locale, $func)                                                                                                  Set the current locale to the given, execute the passed function, reset the locale to previous one,
 *                                                                                                                                                                                               then return the result of the closure (or null if the closure was void).
 * @method Carbon                                             fromSerialized($value)                                                                                                             Create an instance from a serialized string.
 * @method void                                               genericMacro($macro, $priority = 0)                                                                                                Register a custom macro.
 * @method array                                              getAvailableLocales()                                                                                                              Returns the list of internally available locales and already loaded custom locales.
 *                                                                                                                                                                                               (It will ignore custom translator dynamic loading.)
 * @method Language[]                                         getAvailableLocalesInfo()                                                                                                          Returns list of Language object for each available locale. This object allow you to get the ISO name, native
 *                                                                                                                                                                                               name, region and variant of the locale.
 * @method array                                              getDays()                                                                                                                          Get the days of the week
 * @method string|null                                        getFallbackLocale()                                                                                                                Get the fallback locale.
 * @method array                                              getFormatsToIsoReplacements()                                                                                                      List of replacements from date() format to isoFormat().
 * @method int                                                getHumanDiffOptions()                                                                                                              Return default humanDiff() options (merged flags as integer).
 * @method array                                              getIsoUnits()                                                                                                                      Returns list of locale units for ISO formatting.
 * @method Carbon                                             getLastErrors()                                                                                                                    {@inheritdoc}
 * @method string                                             getLocale()                                                                                                                        Get the current translator locale.
 * @method int                                                getMidDayAt()                                                                                                                      get midday/noon hour
 * @method Carbon                                             getTestNow()                                                                                                                       Get the Carbon instance (real or mock) to be returned when a "now"
 *                                                                                                                                                                                               instance is created.
 * @method string                                             getTranslationMessageWith($translator, string $key, string $locale = null, string $default = null)                                 Returns raw translation message for a given key.
 * @method \Symfony\Component\Translation\TranslatorInterface getTranslator()                                                                                                                    Get the default translator instance in use.
 * @method int                                                getWeekEndsAt()                                                                                                                    Get the last day of week
 * @method int                                                getWeekStartsAt()                                                                                                                  Get the first day of week
 * @method array                                              getWeekendDays()                                                                                                                   Get weekend days
 * @method bool                                               hasFormat($date, $format)                                                                                                          Checks if the (date)time string is in a given format.
 * @method bool                                               hasMacro($name)                                                                                                                    Checks if macro is registered.
 * @method bool                                               hasRelativeKeywords($time)                                                                                                         Determine if a time string will produce a relative date.
 * @method bool                                               hasTestNow()                                                                                                                       Determine if there is a valid test instance set. A valid test instance
 *                                                                                                                                                                                               is anything that is not null.
 * @method Carbon                                             instance($date)                                                                                                                    Create a Carbon instance from a DateTime one.
 * @method bool                                               isImmutable()                                                                                                                      Returns true if the current class/instance is immutable.
 * @method bool                                               isModifiableUnit($unit)                                                                                                            Returns true if a property can be changed via setter.
 * @method Carbon                                             isMutable()
 * @method bool                                               isStrictModeEnabled()                                                                                                              Returns true if the strict mode is globally in use, false else.
 *                                                                                                                                                                                               (It can be overridden in specific instances.)
 * @method bool                                               localeHasDiffOneDayWords($locale)                                                                                                  Returns true if the given locale is internally supported and has words for 1-day diff (just now, yesterday, tomorrow).
 *                                                                                                                                                                                               Support is considered enabled if the 3 words are translated in the given locale.
 * @method bool                                               localeHasDiffSyntax($locale)                                                                                                       Returns true if the given locale is internally supported and has diff syntax support (ago, from now, before, after).
 *                                                                                                                                                                                               Support is considered enabled if the 4 sentences are translated in the given locale.
 * @method bool                                               localeHasDiffTwoDayWords($locale)                                                                                                  Returns true if the given locale is internally supported and has words for 2-days diff (before yesterday, after tomorrow).
 *                                                                                                                                                                                               Support is considered enabled if the 2 words are translated in the given locale.
 * @method bool                                               localeHasPeriodSyntax($locale)                                                                                                     Returns true if the given locale is internally supported and has period syntax support (X times, every X, from X, to X).
 *                                                                                                                                                                                               Support is considered enabled if the 4 sentences are translated in the given locale.
 * @method bool                                               localeHasShortUnits($locale)                                                                                                       Returns true if the given locale is internally supported and has short-units support.
 *                                                                                                                                                                                               Support is considered enabled if either year, day or hour has a short variant translated.
 * @method void                                               macro($name, $macro)                                                                                                               Register a custom macro.
 * @method Carbon|null                                        make($var)                                                                                                                         Make a Carbon instance from given variable if possible.
 *                                                                                                                                                                                               Always return a new instance. Parse only strings and only these likely to be dates (skip intervals
 *                                                                                                                                                                                               and recurrences). Throw an exception for invalid format, but otherwise return null.
 * @method Carbon                                             maxValue()                                                                                                                         Create a Carbon instance for the greatest supported date.
 * @method Carbon                                             minValue()                                                                                                                         Create a Carbon instance for the lowest supported date.
 * @method void                                               mixin($mixin)                                                                                                                      Mix another object into the class.
 * @method Carbon                                             now($tz = null)                                                                                                                    Get a Carbon instance for the current date and time.
 * @method Carbon                                             parse($time = null, $tz = null)                                                                                                    Create a carbon instance from a string.
 *                                                                                                                                                                                               This is an alias for the constructor that allows better fluent syntax
 *                                                                                                                                                                                               as it allows you to do Carbon::parse('Monday next week')->fn() rather
 *                                                                                                                                                                                               than (new Carbon('Monday next week'))->fn().
 * @method Carbon                                             parseFromLocale($time, $locale, $tz = null)                                                                                        Create a carbon instance from a localized string (in French, Japanese, Arabic, etc.).
 * @method string                                             pluralUnit(string $unit)                                                                                                           Returns standardized plural of a given singular/plural unit name (in English).
 * @method Carbon|false                                       rawCreateFromFormat($format, $time, $tz = null)                                                                                    Create a Carbon instance from a specific format.
 * @method Carbon                                             rawParse($time = null, $tz = null)                                                                                                 Create a carbon instance from a string.
 *                                                                                                                                                                                               This is an alias for the constructor that allows better fluent syntax
 *                                                                                                                                                                                               as it allows you to do Carbon::parse('Monday next week')->fn() rather
 *                                                                                                                                                                                               than (new Carbon('Monday next week'))->fn().
 * @method Carbon                                             resetMacros()                                                                                                                      Remove all macros and generic macros.
 * @method void                                               resetMonthsOverflow()                                                                                                              @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 *                                                                                                                                                                                                           Or you can use method variants: addMonthsWithOverflow/addMonthsNoOverflow, same variants
 *                                                                                                                                                                                                           are available for quarters, years, decade, centuries, millennia (singular and plural forms).
 * @method void                                               resetToStringFormat()                                                                                                              Reset the format used to the default when type juggling a Carbon instance to a string
 * @method void                                               resetYearsOverflow()                                                                                                               @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 *                                                                                                                                                                                                           Or you can use method variants: addYearsWithOverflow/addYearsNoOverflow, same variants
 *                                                                                                                                                                                                           are available for quarters, years, decade, centuries, millennia (singular and plural forms).
 * @method void                                               serializeUsing($callback)                                                                                                          @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather transform Carbon object before the serialization.
 *                                                                                                                                                                                               JSON serialize all Carbon instances using the given callback.
 * @method Carbon                                             setFallbackLocale($locale)                                                                                                         Set the fallback locale.
 * @method Carbon                                             setHumanDiffOptions($humanDiffOptions)                                                                                             @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 * @method bool                                               setLocale($locale)                                                                                                                 Set the current translator locale and indicate if the source locale file exists.
 *                                                                                                                                                                                               Pass 'auto' as locale to use closest language from the current LC_TIME locale.
 * @method void                                               setMidDayAt($hour)                                                                                                                 @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather consider mid-day is always 12pm, then if you need to test if it's an other
 *                                                                                                                                                                                                           hour, test it explicitly:
 *                                                                                                                                                                                                               $date->format('G') == 13
 *                                                                                                                                                                                                           or to set explicitly to a given hour:
 *                                                                                                                                                                                                               $date->setTime(13, 0, 0, 0)
 *                                                                                                                                                                                               Set midday/noon hour
 * @method Carbon                                             setTestNow($testNow = null)                                                                                                        Set a Carbon instance (real or mock) to be returned when a "now"
 *                                                                                                                                                                                               instance is created.  The provided instance will be returned
 *                                                                                                                                                                                               specifically under the following conditions:
 *                                                                                                                                                                                                 - A call to the static now() method, ex. Carbon::now()
 *                                                                                                                                                                                                 - When a null (or blank string) is passed to the constructor or parse(), ex. new Carbon(null)
 *                                                                                                                                                                                                 - When the string "now" is passed to the constructor or parse(), ex. new Carbon('now')
 *                                                                                                                                                                                                 - When a string containing the desired time is passed to Carbon::parse().
 *                                                                                                                                                                                               Note the timezone parameter was left out of the examples above and
 *                                                                                                                                                                                               has no affect as the mock value will be returned regardless of its value.
 *                                                                                                                                                                                               To clear the test instance call this method using the default
 *                                                                                                                                                                                               parameter of null.
 *                                                                                                                                                                                               /!\ Use this method for unit tests only.
 * @method void                                               setToStringFormat($format)                                                                                                         @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather let Carbon object being casted to string with DEFAULT_TO_STRING_FORMAT, and
 *                                                                                                                                                                                                           use other method or custom format passed to format() method if you need to dump an other string
 *                                                                                                                                                                                                           format.
 *                                                                                                                                                                                               Set the default format used when type juggling a Carbon instance to a string
 * @method void                                               setTranslator(\Symfony\Component\Translation\TranslatorInterface $translator)                                                      Set the default translator instance to use.
 * @method Carbon                                             setUtf8($utf8)                                                                                                                     @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use UTF-8 language packages on every machine.
 *                                                                                                                                                                                               Set if UTF8 will be used for localized date/time.
 * @method void                                               setWeekEndsAt($day)                                                                                                                @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           Use $weekStartsAt optional parameter instead when using startOfWeek, floorWeek, ceilWeek
 *                                                                                                                                                                                                           or roundWeek method. You can also use the 'first_day_of_week' locale setting to change the
 *                                                                                                                                                                                                           start of week according to current locale selected and implicitly the end of week.
 *                                                                                                                                                                                               Set the last day of week
 * @method void                                               setWeekStartsAt($day)                                                                                                              @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           Use $weekEndsAt optional parameter instead when using endOfWeek method. You can also use the
 *                                                                                                                                                                                                           'first_day_of_week' locale setting to change the start of week according to current locale
 *                                                                                                                                                                                                           selected and implicitly the end of week.
 *                                                                                                                                                                                               Set the first day of week
 * @method void                                               setWeekendDays($days)                                                                                                              @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather consider week-end is always saturday and sunday, and if you have some custom
 *                                                                                                                                                                                                           week-end days to handle, give to those days an other name and create a macro for them:
 *                                                                                                                                                                                                           ```
 *                                                                                                                                                                                                           Carbon::macro('isDayOff', function ($date) {
 *                                                                                                                                                                                                               return $date->isSunday() || $date->isMonday();
 *                                                                                                                                                                                                           });
 *                                                                                                                                                                                                           Carbon::macro('isNotDayOff', function ($date) {
 *                                                                                                                                                                                                               return !$date->isDayOff();
 *                                                                                                                                                                                                           });
 *                                                                                                                                                                                                           if ($someDate->isDayOff()) ...
 *                                                                                                                                                                                                           if ($someDate->isNotDayOff()) ...
 *                                                                                                                                                                                                           // Add 5 not-off days
 *                                                                                                                                                                                                           $count = 5;
 *                                                                                                                                                                                                           while ($someDate->isDayOff() || ($count-- > 0)) {
 *                                                                                                                                                                                                               $someDate->addDay();
 *                                                                                                                                                                                                           }
 *                                                                                                                                                                                                           ```
 *                                                                                                                                                                                               Set weekend days
 * @method bool                                               shouldOverflowMonths()                                                                                                             Get the month overflow global behavior (can be overridden in specific instances).
 * @method bool                                               shouldOverflowYears()                                                                                                              Get the month overflow global behavior (can be overridden in specific instances).
 * @method string                                             singularUnit(string $unit)                                                                                                         Returns standardized singular of a given singular/plural unit name (in English).
 * @method Carbon                                             today($tz = null)                                                                                                                  Create a Carbon instance for today.
 * @method Carbon                                             tomorrow($tz = null)                                                                                                               Create a Carbon instance for tomorrow.
 * @method string                                             translateTimeString($timeString, $from = null, $to = null, $mode = 15)                                                             Translate a time string from a locale to an other.
 * @method string                                             translateWith(\Symfony\Component\Translation\TranslatorInterface $translator, string $key, array $parameters = [], $number = null) Translate using translation string or callback available.
 * @method void                                               useMonthsOverflow($monthsOverflow = true)                                                                                          @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 *                                                                                                                                                                                                           Or you can use method variants: addMonthsWithOverflow/addMonthsNoOverflow, same variants
 *                                                                                                                                                                                                           are available for quarters, years, decade, centuries, millennia (singular and plural forms).
 * @method Carbon                                             useStrictMode($strictModeEnabled = true)                                                                                           @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 * @method void                                               useYearsOverflow($yearsOverflow = true)                                                                                            @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 *                                                                                                                                                                                                           Or you can use method variants: addYearsWithOverflow/addYearsNoOverflow, same variants
 *                                                                                                                                                                                                           are available for quarters, years, decade, centuries, millennia (singular and plural forms).
 * @method Carbon                                             yesterday($tz = null)                                                                                                              Create a Carbon instance for yesterday.
 *
 * </autodoc>
 */
class Factory
{
    protected $className = Carbon::class;

    protected $settings = [];

    public function __construct(array $settings = [], string $className = null)
    {
        if ($className) {
            $this->className = $className;
        }
        $this->settings = $settings;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function setClassName(string $className)
    {
        $this->className = $className;

        return $this;
    }

    public function className(string $className = null)
    {
        return $className === null ? $this->getClassName() : $this->setClassName($className);
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function setSettings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    public function settings(array $settings = null)
    {
        return $settings === null ? $this->getSettings() : $this->setSettings($settings);
    }

    public function mergeSettings(array $settings)
    {
        $this->settings = array_merge($this->settings, $settings);

        return $this;
    }

    public function __call($name, $arguments)
    {
        $result = $this->className::$name(...$arguments);

        return $result instanceof CarbonInterface ? $result->settings($this->settings) : $result;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon;

/**
 * A factory to generate CarbonImmutable instances with common settings.
 *
 * <autodoc generated by `composer phpdoc`>
 *
 * @method CarbonImmutable                                    create($year = 0, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0, $tz = null)                                           Create a new Carbon instance from a specific date and time.
 *                                                                                                                                                                                               If any of $year, $month or $day are set to null their now() values will
 *                                                                                                                                                                                               be used.
 *                                                                                                                                                                                               If $hour is null it will be set to its now() value and the default
 *                                                                                                                                                                                               values for $minute and $second will be their now() values.
 *                                                                                                                                                                                               If $hour is not null then the default values for $minute and $second
 *                                                                                                                                                                                               will be 0.
 * @method CarbonImmutable                                    createFromDate($year = null, $month = null, $day = null, $tz = null)                                                               Create a Carbon instance from just a date. The time portion is set to now.
 * @method CarbonImmutable|false                              createFromFormat($format, $time, $tz = null)                                                                                       Create a Carbon instance from a specific format.
 * @method CarbonImmutable|false                              createFromIsoFormat($format, $time, $tz = null, $locale = 'en', $translator = null)                                                Create a Carbon instance from a specific ISO format (same replacements as ->isoFormat()).
 * @method CarbonImmutable|false                              createFromLocaleFormat($format, $locale, $time, $tz = null)                                                                        Create a Carbon instance from a specific format and a string in a given language.
 * @method CarbonImmutable|false                              createFromLocaleIsoFormat($format, $locale, $time, $tz = null)                                                                     Create a Carbon instance from a specific ISO format and a string in a given language.
 * @method CarbonImmutable                                    createFromTime($hour = 0, $minute = 0, $second = 0, $tz = null)                                                                    Create a Carbon instance from just a time. The date portion is set to today.
 * @method CarbonImmutable                                    createFromTimeString($time, $tz = null)                                                                                            Create a Carbon instance from a time string. The date portion is set to today.
 * @method CarbonImmutable                                    createFromTimestamp($timestamp, $tz = null)                                                                                        Create a Carbon instance from a timestamp.
 * @method CarbonImmutable                                    createFromTimestampMs($timestamp, $tz = null)                                                                                      Create a Carbon instance from a timestamp in milliseconds.
 * @method CarbonImmutable                                    createFromTimestampUTC($timestamp)                                                                                                 Create a Carbon instance from an UTC timestamp.
 * @method CarbonImmutable                                    createMidnightDate($year = null, $month = null, $day = null, $tz = null)                                                           Create a Carbon instance from just a date. The time portion is set to midnight.
 * @method CarbonImmutable|false                              createSafe($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $tz = null)                     Create a new safe Carbon instance from a specific date and time.
 *                                                                                                                                                                                               If any of $year, $month or $day are set to null their now() values will
 *                                                                                                                                                                                               be used.
 *                                                                                                                                                                                               If $hour is null it will be set to its now() value and the default
 *                                                                                                                                                                                               values for $minute and $second will be their now() values.
 *                                                                                                                                                                                               If $hour is not null then the default values for $minute and $second
 *                                                                                                                                                                                               will be 0.
 *                                                                                                                                                                                               If one of the set values is not valid, an \InvalidArgumentException
 *                                                                                                                                                                                               will be thrown.
 * @method CarbonImmutable                                    disableHumanDiffOption($humanDiffOption)                                                                                           @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 * @method CarbonImmutable                                    enableHumanDiffOption($humanDiffOption)                                                                                            @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 * @method mixed                                              executeWithLocale($locale, $func)                                                                                                  Set the current locale to the given, execute the passed function, reset the locale to previous one,
 *                                                                                                                                                                                               then return the result of the closure (or null if the closure was void).
 * @method CarbonImmutable                                    fromSerialized($value)                                                                                                             Create an instance from a serialized string.
 * @method void                                               genericMacro($macro, $priority = 0)                                                                                                Register a custom macro.
 * @method array                                              getAvailableLocales()                                                                                                              Returns the list of internally available locales and already loaded custom locales.
 *                                                                                                                                                                                               (It will ignore custom translator dynamic loading.)
 * @method Language[]                                         getAvailableLocalesInfo()                                                                                                          Returns list of Language object for each available locale. This object allow you to get the ISO name, native
 *                                                                                                                                                                                               name, region and variant of the locale.
 * @method array                                              getDays()                                                                                                                          Get the days of the week
 * @method string|null                                        getFallbackLocale()                                                                                                                Get the fallback locale.
 * @method array                                              getFormatsToIsoReplacements()                                                                                                      List of replacements from date() format to isoFormat().
 * @method int                                                getHumanDiffOptions()                                                                                                              Return default humanDiff() options (merged flags as integer).
 * @method array                                              getIsoUnits()                                                                                                                      Returns list of locale units for ISO formatting.
 * @method CarbonImmutable                                    getLastErrors()                                                                                                                    {@inheritdoc}
 * @method string                                             getLocale()                                                                                                                        Get the current translator locale.
 * @method int                                                getMidDayAt()                                                                                                                      get midday/noon hour
 * @method CarbonImmutable                                    getTestNow()                                                                                                                       Get the Carbon instance (real or mock) to be returned when a "now"
 *                                                                                                                                                                                               instance is created.
 * @method string                                             getTranslationMessageWith($translator, string $key, string $locale = null, string $default = null)                                 Returns raw translation message for a given key.
 * @method \Symfony\Component\Translation\TranslatorInterface getTranslator()                                                                                                                    Get the default translator instance in use.
 * @method int                                                getWeekEndsAt()                                                                                                                    Get the last day of week
 * @method int                                                getWeekStartsAt()                                                                                                                  Get the first day of week
 * @method array                                              getWeekendDays()                                                                                                                   Get weekend days
 * @method bool                                               hasFormat($date, $format)                                                                                                          Checks if the (date)time string is in a given format.
 * @method bool                                               hasMacro($name)                                                                                                                    Checks if macro is registered.
 * @method bool                                               hasRelativeKeywords($time)                                                                                                         Determine if a time string will produce a relative date.
 * @method bool                                               hasTestNow()                                                                                                                       Determine if there is a valid test instance set. A valid test instance
 *                                                                                                                                                                                               is anything that is not null.
 * @method CarbonImmutable                                    instance($date)                                                                                                                    Create a Carbon instance from a DateTime one.
 * @method bool                                               isImmutable()                                                                                                                      Returns true if the current class/instance is immutable.
 * @method bool                                               isModifiableUnit($unit)                                                                                                            Returns true if a property can be changed via setter.
 * @method CarbonImmutable                                    isMutable()
 * @method bool                                               isStrictModeEnabled()                                                                                                              Returns true if the strict mode is globally in use, false else.
 *                                                                                                                                                                                               (It can be overridden in specific instances.)
 * @method bool                                               localeHasDiffOneDayWords($locale)                                                                                                  Returns true if the given locale is internally supported and has words for 1-day diff (just now, yesterday, tomorrow).
 *                                                                                                                                                                                               Support is considered enabled if the 3 words are translated in the given locale.
 * @method bool                                               localeHasDiffSyntax($locale)                                                                                                       Returns true if the given locale is internally supported and has diff syntax support (ago, from now, before, after).
 *                                                                                                                                                                                               Support is considered enabled if the 4 sentences are translated in the given locale.
 * @method bool                                               localeHasDiffTwoDayWords($locale)                                                                                                  Returns true if the given locale is internally supported and has words for 2-days diff (before yesterday, after tomorrow).
 *                                                                                                                                                                                               Support is considered enabled if the 2 words are translated in the given locale.
 * @method bool                                               localeHasPeriodSyntax($locale)                                                                                                     Returns true if the given locale is internally supported and has period syntax support (X times, every X, from X, to X).
 *                                                                                                                                                                                               Support is considered enabled if the 4 sentences are translated in the given locale.
 * @method bool                                               localeHasShortUnits($locale)                                                                                                       Returns true if the given locale is internally supported and has short-units support.
 *                                                                                                                                                                                               Support is considered enabled if either year, day or hour has a short variant translated.
 * @method void                                               macro($name, $macro)                                                                                                               Register a custom macro.
 * @method CarbonImmutable|null                               make($var)                                                                                                                         Make a Carbon instance from given variable if possible.
 *                                                                                                                                                                                               Always return a new instance. Parse only strings and only these likely to be dates (skip intervals
 *                                                                                                                                                                                               and recurrences). Throw an exception for invalid format, but otherwise return null.
 * @method CarbonImmutable                                    maxValue()                                                                                                                         Create a Carbon instance for the greatest supported date.
 * @method CarbonImmutable                                    minValue()                                                                                                                         Create a Carbon instance for the lowest supported date.
 * @method void                                               mixin($mixin)                                                                                                                      Mix another object into the class.
 * @method CarbonImmutable                                    now($tz = null)                                                                                                                    Get a Carbon instance for the current date and time.
 * @method CarbonImmutable                                    parse($time = null, $tz = null)                                                                                                    Create a carbon instance from a string.
 *                                                                                                                                                                                               This is an alias for the constructor that allows better fluent syntax
 *                                                                                                                                                                                               as it allows you to do Carbon::parse('Monday next week')->fn() rather
 *                                                                                                                                                                                               than (new Carbon('Monday next week'))->fn().
 * @method CarbonImmutable                                    parseFromLocale($time, $locale, $tz = null)                                                                                        Create a carbon instance from a localized string (in French, Japanese, Arabic, etc.).
 * @method string                                             pluralUnit(string $unit)                                                                                                           Returns standardized plural of a given singular/plural unit name (in English).
 * @method CarbonImmutable|false                              rawCreateFromFormat($format, $time, $tz = null)                                                                                    Create a Carbon instance from a specific format.
 * @method CarbonImmutable                                    rawParse($time = null, $tz = null)                                                                                                 Create a carbon instance from a string.
 *                                                                                                                                                                                               This is an alias for the constructor that allows better fluent syntax
 *                                                                                                                                                                                               as it allows you to do Carbon::parse('Monday next week')->fn() rather
 *                                                                                                                                                                                               than (new Carbon('Monday next week'))->fn().
 * @method CarbonImmutable                                    resetMacros()                                                                                                                      Remove all macros and generic macros.
 * @method void                                               resetMonthsOverflow()                                                                                                              @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 *                                                                                                                                                                                                           Or you can use method variants: addMonthsWithOverflow/addMonthsNoOverflow, same variants
 *                                                                                                                                                                                                           are available for quarters, years, decade, centuries, millennia (singular and plural forms).
 * @method void                                               resetToStringFormat()                                                                                                              Reset the format used to the default when type juggling a Carbon instance to a string
 * @method void                                               resetYearsOverflow()                                                                                                               @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 *                                                                                                                                                                                                           Or you can use method variants: addYearsWithOverflow/addYearsNoOverflow, same variants
 *                                                                                                                                                                                                           are available for quarters, years, decade, centuries, millennia (singular and plural forms).
 * @method void                                               serializeUsing($callback)                                                                                                          @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather transform Carbon object before the serialization.
 *                                                                                                                                                                                               JSON serialize all Carbon instances using the given callback.
 * @method CarbonImmutable                                    setFallbackLocale($locale)                                                                                                         Set the fallback locale.
 * @method CarbonImmutable                                    setHumanDiffOptions($humanDiffOptions)                                                                                             @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 * @method bool                                               setLocale($locale)                                                                                                                 Set the current translator locale and indicate if the source locale file exists.
 *                                                                                                                                                                                               Pass 'auto' as locale to use closest language from the current LC_TIME locale.
 * @method void                                               setMidDayAt($hour)                                                                                                                 @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather consider mid-day is always 12pm, then if you need to test if it's an other
 *                                                                                                                                                                                                           hour, test it explicitly:
 *                                                                                                                                                                                                               $date->format('G') == 13
 *                                                                                                                                                                                                           or to set explicitly to a given hour:
 *                                                                                                                                                                                                               $date->setTime(13, 0, 0, 0)
 *                                                                                                                                                                                               Set midday/noon hour
 * @method CarbonImmutable                                    setTestNow($testNow = null)                                                                                                        Set a Carbon instance (real or mock) to be returned when a "now"
 *                                                                                                                                                                                               instance is created.  The provided instance will be returned
 *                                                                                                                                                                                               specifically under the following conditions:
 *                                                                                                                                                                                                 - A call to the static now() method, ex. Carbon::now()
 *                                                                                                                                                                                                 - When a null (or blank string) is passed to the constructor or parse(), ex. new Carbon(null)
 *                                                                                                                                                                                                 - When the string "now" is passed to the constructor or parse(), ex. new Carbon('now')
 *                                                                                                                                                                                                 - When a string containing the desired time is passed to Carbon::parse().
 *                                                                                                                                                                                               Note the timezone parameter was left out of the examples above and
 *                                                                                                                                                                                               has no affect as the mock value will be returned regardless of its value.
 *                                                                                                                                                                                               To clear the test instance call this method using the default
 *                                                                                                                                                                                               parameter of null.
 *                                                                                                                                                                                               /!\ Use this method for unit tests only.
 * @method void                                               setToStringFormat($format)                                                                                                         @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather let Carbon object being casted to string with DEFAULT_TO_STRING_FORMAT, and
 *                                                                                                                                                                                                           use other method or custom format passed to format() method if you need to dump an other string
 *                                                                                                                                                                                                           format.
 *                                                                                                                                                                                               Set the default format used when type juggling a Carbon instance to a string
 * @method void                                               setTranslator(\Symfony\Component\Translation\TranslatorInterface $translator)                                                      Set the default translator instance to use.
 * @method CarbonImmutable                                    setUtf8($utf8)                                                                                                                     @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use UTF-8 language packages on every machine.
 *                                                                                                                                                                                               Set if UTF8 will be used for localized date/time.
 * @method void                                               setWeekEndsAt($day)                                                                                                                @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           Use $weekStartsAt optional parameter instead when using startOfWeek, floorWeek, ceilWeek
 *                                                                                                                                                                                                           or roundWeek method. You can also use the 'first_day_of_week' locale setting to change the
 *                                                                                                                                                                                                           start of week according to current locale selected and implicitly the end of week.
 *                                                                                                                                                                                               Set the last day of week
 * @method void                                               setWeekStartsAt($day)                                                                                                              @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           Use $weekEndsAt optional parameter instead when using endOfWeek method. You can also use the
 *                                                                                                                                                                                                           'first_day_of_week' locale setting to change the start of week according to current locale
 *                                                                                                                                                                                                           selected and implicitly the end of week.
 *                                                                                                                                                                                               Set the first day of week
 * @method void                                               setWeekendDays($days)                                                                                                              @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather consider week-end is always saturday and sunday, and if you have some custom
 *                                                                                                                                                                                                           week-end days to handle, give to those days an other name and create a macro for them:
 *                                                                                                                                                                                                           ```
 *                                                                                                                                                                                                           Carbon::macro('isDayOff', function ($date) {
 *                                                                                                                                                                                                               return $date->isSunday() || $date->isMonday();
 *                                                                                                                                                                                                           });
 *                                                                                                                                                                                                           Carbon::macro('isNotDayOff', function ($date) {
 *                                                                                                                                                                                                               return !$date->isDayOff();
 *                                                                                                                                                                                                           });
 *                                                                                                                                                                                                           if ($someDate->isDayOff()) ...
 *                                                                                                                                                                                                           if ($someDate->isNotDayOff()) ...
 *                                                                                                                                                                                                           // Add 5 not-off days
 *                                                                                                                                                                                                           $count = 5;
 *                                                                                                                                                                                                           while ($someDate->isDayOff() || ($count-- > 0)) {
 *                                                                                                                                                                                                               $someDate->addDay();
 *                                                                                                                                                                                                           }
 *                                                                                                                                                                                                           ```
 *                                                                                                                                                                                               Set weekend days
 * @method bool                                               shouldOverflowMonths()                                                                                                             Get the month overflow global behavior (can be overridden in specific instances).
 * @method bool                                               shouldOverflowYears()                                                                                                              Get the month overflow global behavior (can be overridden in specific instances).
 * @method string                                             singularUnit(string $unit)                                                                                                         Returns standardized singular of a given singular/plural unit name (in English).
 * @method CarbonImmutable                                    today($tz = null)                                                                                                                  Create a Carbon instance for today.
 * @method CarbonImmutable                                    tomorrow($tz = null)                                                                                                               Create a Carbon instance for tomorrow.
 * @method string                                             translateTimeString($timeString, $from = null, $to = null, $mode = 15)                                                             Translate a time string from a locale to an other.
 * @method string                                             translateWith(\Symfony\Component\Translation\TranslatorInterface $translator, string $key, array $parameters = [], $number = null) Translate using translation string or callback available.
 * @method void                                               useMonthsOverflow($monthsOverflow = true)                                                                                          @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 *                                                                                                                                                                                                           Or you can use method variants: addMonthsWithOverflow/addMonthsNoOverflow, same variants
 *                                                                                                                                                                                                           are available for quarters, years, decade, centuries, millennia (singular and plural forms).
 * @method CarbonImmutable                                    useStrictMode($strictModeEnabled = true)                                                                                           @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 * @method void                                               useYearsOverflow($yearsOverflow = true)                                                                                            @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                                                           You should rather use the ->settings() method.
 *                                                                                                                                                                                                           Or you can use method variants: addYearsWithOverflow/addYearsNoOverflow, same variants
 *                                                                                                                                                                                                           are available for quarters, years, decade, centuries, millennia (singular and plural forms).
 * @method CarbonImmutable                                    yesterday($tz = null)                                                                                                              Create a Carbon instance for yesterday.
 *
 * </autodoc>
 */
class FactoryImmutable extends Factory
{
    protected $className = CarbonImmutable::class;
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon;

use JsonSerializable;

class Language implements JsonSerializable
{
    /**
     * @var array
     */
    protected static $languagesNames;

    /**
     * @var array
     */
    protected static $regionsNames;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string|null
     */
    protected $variant;

    /**
     * @var string|null
     */
    protected $region;

    /**
     * @var array
     */
    protected $names;

    /**
     * @var string
     */
    protected $isoName;

    /**
     * @var string
     */
    protected $nativeName;

    public function __construct(string $id)
    {
        $this->id = str_replace('-', '_', $id);
        $parts = explode('_', $this->id);
        $this->code = $parts[0];

        if (isset($parts[1])) {
            if (!preg_match('/^[A-Z]+$/', $parts[1])) {
                $this->variant = $parts[1];
                $parts[1] = $parts[2] ?? null;
            }
            if ($parts[1]) {
                $this->region = $parts[1];
            }
        }
    }

    /**
     * Get the list of the known languages.
     *
     * @return array
     */
    public static function all()
    {
        if (!static::$languagesNames) {
            static::$languagesNames = include __DIR__.'/List/languages.php';
        }

        return static::$languagesNames;
    }

    /**
     * Get the list of the known regions.
     *
     * @return array
     */
    public static function regions()
    {
        if (!static::$regionsNames) {
            static::$regionsNames = include __DIR__.'/List/regions.php';
        }

        return static::$regionsNames;
    }

    /**
     * Get both isoName and nativeName as an array.
     *
     * @return array
     */
    public function getNames(): array
    {
        if (!$this->names) {
            $this->names = static::all()[$this->code] ?? [
                'isoName' => $this->code,
                'nativeName' => $this->code,
            ];
        }

        return $this->names;
    }

    /**
     * Returns the original locale ID.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Returns the code of the locale "en"/"fr".
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Returns the variant code such as cyrl/latn.
     *
     * @return string|null
     */
    public function getVariant(): ?string
    {
        return $this->variant;
    }

    /**
     * Returns the variant such as Cyrillic/Latin.
     *
     * @return string|null
     */
    public function getVariantName(): ?string
    {
        if ($this->variant === 'Latn') {
            return 'Latin';
        }

        if ($this->variant === 'Cyrl') {
            return 'Cyrillic';
        }

        return $this->variant;
    }

    /**
     * Returns the region part of the locale.
     *
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * Returns the region name for the current language.
     *
     * @return string|null
     */
    public function getRegionName(): ?string
    {
        return $this->region ? (static::regions()[$this->region] ?? $this->region) : null;
    }

    /**
     * Returns the long ISO language name.
     *
     * @return string
     */
    public function getFullIsoName(): string
    {
        if (!$this->isoName) {
            $this->isoName = $this->getNames()['isoName'];
        }

        return $this->isoName;
    }

    /**
     * Set the ISO language name.
     *
     * @param string $isoName
     */
    public function setIsoName(string $isoName): self
    {
        $this->isoName = $isoName;

        return $this;
    }

    /**
     * Return the full name of the language in this language.
     *
     * @return string
     */
    public function getFullNativeName(): string
    {
        if (!$this->nativeName) {
            $this->nativeName = $this->getNames()['nativeName'];
        }

        return $this->nativeName;
    }

    /**
     * Set the name of the language in this language.
     *
     * @param string $nativeName
     */
    public function setNativeName(string $nativeName): self
    {
        $this->nativeName = $nativeName;

        return $this;
    }

    /**
     * Returns the short ISO language name.
     *
     * @return string
     */
    public function getIsoName(): string
    {
        $name = $this->getFullIsoName();

        return trim(strstr($name, ',', true) ?: $name);
    }

    /**
     * Get the short name of the language in this language.
     *
     * @return string
     */
    public function getNativeName(): string
    {
        $name = $this->getFullNativeName();

        return trim(strstr($name, ',', true) ?: $name);
    }

    /**
     * Get a string with short ISO name, region in parentheses if applicable, variant in parentheses if applicable.
     *
     * @return string
     */
    public function getIsoDescription()
    {
        $region = $this->getRegionName();
        $variant = $this->getVariantName();

        return $this->getIsoName().($region ? ' ('.$region.')' : '').($variant ? ' ('.$variant.')' : '');
    }

    /**
     * Get a string with short native name, region in parentheses if applicable, variant in parentheses if applicable.
     *
     * @return string
     */
    public function getNativeDescription()
    {
        $region = $this->getRegionName();
        $variant = $this->getVariantName();

        return $this->getNativeName().($region ? ' ('.$region.')' : '').($variant ? ' ('.$variant.')' : '');
    }

    /**
     * Get a string with long ISO name, region in parentheses if applicable, variant in parentheses if applicable.
     *
     * @return string
     */
    public function getFullIsoDescription()
    {
        $region = $this->getRegionName();
        $variant = $this->getVariantName();

        return $this->getFullIsoName().($region ? ' ('.$region.')' : '').($variant ? ' ('.$variant.')' : '');
    }

    /**
     * Get a string with long native name, region in parentheses if applicable, variant in parentheses if applicable.
     *
     * @return string
     */
    public function getFullNativeDescription()
    {
        $region = $this->getRegionName();
        $variant = $this->getVariantName();

        return $this->getFullNativeName().($region ? ' ('.$region.')' : '').($variant ? ' ('.$variant.')' : '');
    }

    /**
     * Returns the original locale ID.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getId();
    }

    /**
     * Get a string with short ISO name, region in parentheses if applicable, variant in parentheses if applicable.
     *
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->getIsoDescription();
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon;

use Closure;
use ReflectionException;
use ReflectionFunction;
use Symfony\Component\Translation;

class Translator extends Translation\Translator
{
    /**
     * Translator singletons for each language.
     *
     * @var array
     */
    protected static $singletons = [];

    /**
     * List of custom localized messages.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * List of custom directories that contain translation files.
     *
     * @var array
     */
    protected $directories = [];

    /**
     * Set to true while constructing.
     *
     * @var bool
     */
    protected $initializing = false;

    /**
     * Return a singleton instance of Translator.
     *
     * @param string|null $locale optional initial locale ("en" - english by default)
     *
     * @return static
     */
    public static function get($locale = null)
    {
        $locale = $locale ?: 'en';

        if (!isset(static::$singletons[$locale])) {
            static::$singletons[$locale] = new static($locale ?: 'en');
        }

        return static::$singletons[$locale];
    }

    public function __construct($locale, Translation\Formatter\MessageFormatterInterface $formatter = null, $cacheDir = null, $debug = false)
    {
        $this->initializing = true;
        $this->directories = [__DIR__.'/Lang'];
        $this->addLoader('array', new Translation\Loader\ArrayLoader());
        parent::__construct($locale, $formatter, $cacheDir, $debug);
        $this->initializing = false;
    }

    /**
     * Returns the list of directories translation files are searched in.
     *
     * @return array
     */
    public function getDirectories(): array
    {
        return $this->directories;
    }

    /**
     * Set list of directories translation files are searched in.
     *
     * @param array $directories new directories list
     *
     * @return $this
     */
    public function setDirectories(array $directories)
    {
        $this->directories = $directories;

        return $this;
    }

    /**
     * Add a directory to the list translation files are searched in.
     *
     * @param string $directory new directory
     *
     * @return $this
     */
    public function addDirectory(string $directory)
    {
        $this->directories[] = $directory;

        return $this;
    }

    /**
     * Remove a directory from the list translation files are searched in.
     *
     * @param string $directory directory path
     *
     * @return $this
     */
    public function removeDirectory(string $directory)
    {
        $search = rtrim(strtr($directory, '\\', '/'), '/');

        return $this->setDirectories(array_filter($this->getDirectories(), function ($item) use ($search) {
            return rtrim(strtr($item, '\\', '/'), '/') !== $search;
        }));
    }

    /**
     * Returns the translation.
     *
     * @param string $id
     * @param array  $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return string
     */
    public function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        if (null === $domain) {
            $domain = 'messages';
        }

        $format = $this->getCatalogue($locale)->get((string) $id, $domain);

        if ($format instanceof Closure) {
            // @codeCoverageIgnoreStart
            try {
                $count = (new ReflectionFunction($format))->getNumberOfRequiredParameters();
            } catch (ReflectionException $exception) {
                $count = 0;
            }
            // @codeCoverageIgnoreEnd

            return $format(
                ...array_values($parameters),
                ...array_fill(0, max(0, $count - count($parameters)), null)
            );
        }

        return parent::trans($id, $parameters, $domain, $locale);
    }

    /**
     * Reset messages of a locale (all locale if no locale passed).
     * Remove custom messages and reload initial messages from matching
     * file in Lang directory.
     *
     * @param string|null $locale
     *
     * @return bool
     */
    public function resetMessages($locale = null)
    {
        if ($locale === null) {
            $this->messages = [];

            return true;
        }

        foreach ($this->getDirectories() as $directory) {
            $directory = rtrim($directory, '\\/');
            if (file_exists($filename = "$directory/$locale.php")) {
                $this->messages[$locale] = require $filename;
                $this->addResource('array', $this->messages[$locale], $locale);

                return true;
            }
        }

        return false;
    }

    /**
     * Returns the list of files matching a given locale prefix (or all if empty).
     *
     * @param string $prefix prefix required to filter result
     *
     * @return array
     */
    public function getLocalesFiles($prefix = '')
    {
        $files = [];
        foreach ($this->getDirectories() as $directory) {
            $directory = rtrim($directory, '\\/');
            foreach (glob("$directory/$prefix*.php") as $file) {
                $files[] = $file;
            }
        }

        return array_unique($files);
    }

    /**
     * Returns the list of internally available locales and already loaded custom locales.
     * (It will ignore custom translator dynamic loading.)
     *
     * @param string $prefix prefix required to filter result
     *
     * @return array
     */
    public function getAvailableLocales($prefix = '')
    {
        $locales = [];
        foreach ($this->getLocalesFiles($prefix) as $file) {
            $locales[] = substr($file, strrpos($file, '/') + 1, -4);
        }

        return array_unique(array_merge($locales, array_keys($this->messages)));
    }

    /**
     * Init messages language from matching file in Lang directory.
     *
     * @param string $locale
     *
     * @return bool
     */
    protected function loadMessagesFromFile($locale)
    {
        if (isset($this->messages[$locale])) {
            return true;
        }

        return $this->resetMessages($locale);
    }

    /**
     * Set messages of a locale and take file first if present.
     *
     * @param string $locale
     * @param array  $messages
     *
     * @return $this
     */
    public function setMessages($locale, $messages)
    {
        $this->loadMessagesFromFile($locale);
        $this->addResource('array', $messages, $locale);
        $this->messages[$locale] = array_merge(
            isset($this->messages[$locale]) ? $this->messages[$locale] : [],
            $messages
        );

        return $this;
    }

    /**
     * Set messages of the current locale and take file first if present.
     *
     * @param array $messages
     *
     * @return $this
     */
    public function setTranslations($messages)
    {
        return $this->setMessages($this->getLocale(), $messages);
    }

    /**
     * Get messages of a locale, if none given, return all the
     * languages.
     *
     * @param string|null $locale
     *
     * @return array
     */
    public function getMessages($locale = null)
    {
        return $locale === null ? $this->messages : $this->messages[$locale];
    }

    /**
     * Set the current translator locale and indicate if the source locale file exists
     *
     * @param string $locale locale ex. en
     *
     * @return bool
     */
    public function setLocale($locale)
    {
        $locale = preg_replace_callback('/[-_]([a-z]{2,})/', function ($matches) {
            // _2-letters or YUE is a region, _3+-letters is a variant
            $upper = strtoupper($matches[1]);

            if ($upper === 'YUE' || $upper === 'ISO' || strlen($upper) < 3) {
                return "_$upper";
            }

            return '_'.ucfirst($matches[1]);
        }, strtolower($locale));

        if ($this->getLocale() === $locale) {
            return true;
        }

        if ($locale === 'auto') {
            $completeLocale = setlocale(LC_TIME, 0);
            $locale = preg_replace('/^([^_.-]+).*$/', '$1', $completeLocale);
            $locales = $this->getAvailableLocales($locale);

            $completeLocaleChunks = preg_split('/[_.-]+/', $completeLocale);
            $getScore = function ($language) use ($completeLocaleChunks) {
                $chunks = preg_split('/[_.-]+/', $language);
                $score = 0;
                foreach ($completeLocaleChunks as $index => $chunk) {
                    if (!isset($chunks[$index])) {
                        $score++;

                        continue;
                    }
                    if (strtolower($chunks[$index]) === strtolower($chunk)) {
                        $score += 10;
                    }
                }

                return $score;
            };
            usort($locales, function ($a, $b) use ($getScore) {
                $a = $getScore($a);
                $b = $getScore($b);

                if ($a === $b) {
                    return 0;
                }

                return $a < $b ? 1 : -1;
            });
            $locale = $locales[0];
        }

        // If subtag (ex: en_CA) first load the macro (ex: en) to have a fallback
        if (strpos($locale, '_') !== false &&
            $this->loadMessagesFromFile($macroLocale = preg_replace('/^([^_]+).*$/', '$1', $locale))
        ) {
            parent::setLocale($macroLocale);
        }

        if ($this->loadMessagesFromFile($locale) || $this->initializing) {
            parent::setLocale($locale);

            return true;
        }

        return false;
    }

    /**
     * Show locale on var_dump().
     *
     * @return array
     */
    public function __debugInfo()
    {
        return [
            'locale' => $this->getLocale(),
        ];
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Carbon\Exceptions;

use Exception;
use InvalidArgumentException;

class InvalidDateException extends InvalidArgumentException
{
    /**
     * The invalid field.
     *
     * @var string
     */
    private $field;

    /**
     * The invalid value.
     *
     * @var mixed
     */
    private $value;

    /**
     * Constructor.
     *
     * @param string          $field
     * @param mixed           $value
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct($field, $value, $code = 0, Exception $previous = null)
    {
        $this->field = $field;
        $this->value = $value;
        parent::__construct($field.' : '.$value.' is not a valid value.', $code, $previous);
    }

    /**
     * Get the invalid field.
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Get the invalid value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Authors:
 * - Ge'ez Frontier Foundation    locales@geez.org
 */
return array_replace_recursive(require __DIR__.'/en.php', [
    'formats' => [
        'L' => 'DD.MM.YYYY',
    ],
    'months' => ['Qunxa Garablu', 'Kudo', 'Ciggilta Kudo', 'Agda Baxisso', 'Caxah Alsa', 'Qasa Dirri', 'Qado Dirri', 'Liiqen', 'Waysu', 'Diteli', 'Ximoli', 'Kaxxa Garablu'],
    'months_short' => ['qun', 'nah', 'cig', 'agd', 'cax', 'qas', 'qad', 'leq', 'way', 'dit', 'xim', 'kax'],
    'weekdays' => ['Acaada', 'Etleeni', 'Talaata', 'Arbaqa', 'Kamiisi', 'Gumqata', 'Sabti'],
    'weekdays_short' => ['aca', 'etl', 'tal', 'arb', 'kam', 'gum', 'sab'],
    'weekdays_min' => ['aca', 'etl', 'tal', 'arb', 'kam', 'gum', 'sab'],
    'first_day_of_week' => 6,
    'day_of_first_week_of_year' => 1,
    'meridiem' => ['saaku', 'carra'],

    'year' => ':count gaqambo', // less reliable
    'y' => ':count gaqambo', // less reliable
    'a_year' => ':count gaqambo', // less reliable

    'month' => ':count √†lsa',
    'm' => ':count √†lsa',
    'a_month' => ':count √†lsa',

    'day' => ':count saaku', // less reliable
    'd' => ':count saaku', // less reliable
    'a_day' => ':count saaku', // less reliable

    'hour' => ':count ayti', // less reliable
    'h' => ':count ayti', // less reliable
    'a_hour' => ':count ayti', // less reliable
]);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <?php

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Authors:
 * - Ge'ez Frontier Foundation    locales@geez.org
 */
return array_replace_recursive(require __DIR__.'/en.php', [
    'formats' => [
        'L' => 'DD/MM/YYYY',
    ],
    'months' => ['Qunxa Garablu', 'Naharsi Kudo', 'Ciggilta Kudo', 'Agda Baxisso', 'Caxah Alsa', 'Qasa Dirri', 'Qado Dirri', 'Leqeeni', 'Waysu', 'Diteli', 'Ximoli', 'Kaxxa Garablu'],
    'months_short' => ['Qun', 'Nah', 'Cig', 'Agd', 'Cax', 'Qas', 'Qad', 'Leq', 'Way', 'Dit', 'Xim', 'Kax'],
    'weekdays' => ['Acaada', 'Etleeni', 'Talaata', 'Arbaqa', 'Kamiisi', 'Gumqata', 'Sabti'],
    'weekdays_short' => ['Aca', 'Etl', 'Tal', 'Arb', 'Kam', 'Gum', 'Sab'],
    'weekdays_min' => ['Aca', 'Etl', 'Tal', 'Arb', 'Kam', 'Gum', 'Sab'],
    'first_day_of_week' => 1,
    'day_of_first_week_of_year' => 1,
    'meridiem' => ['saaku', 'carra'],
]);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    