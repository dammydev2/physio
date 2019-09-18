<?php

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
 * Trait Boundaries.
 *
 * startOf, endOf and derived method for each unit.
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
 * @method $this setTime(int $hour, int $minute, int $second = 0, int $microseconds = 0)
 * @method $this setDate(int $year, int $month, int $day)
 * @method $this addMonths(int $value = 1)
 */
trait Boundaries
{
    /**
     * Resets the time to 00:00:00 start of day
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfDay();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfDay()
    {
        return $this->setTime(0, 0, 0, 0);
    }

    /**
     * Resets the time to 23:59:59.999999 end of day
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->endOfDay();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function endOfDay()
    {
        return $this->setTime(static::HOURS_PER_DAY - 1, static::MINUTES_PER_HOUR - 1, static::SECONDS_PER_MINUTE - 1, static::MICROSECONDS_PER_SECOND - 1);
    }

    /**
     * Resets the date to the first day of the month and the time to 00:00:00
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfMonth();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function startOfMonth()
    {
        return $this->setDate($this->year, $this->month, 1)->startOfDay();
    }

    /**
     * Resets the date to end of the month and time to 23:59:59.999999
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->endOfMonth();
     * ```
     *
     * @return static|CarbonInterface
     */
    public function endOfMonth()
    {
        return $this->setDate($this->year, $this->month, $this->daysInMonth)->endOfDay();
    }

    /**
     * Resets the date to the first day of the quarter and the time to 00:00:00
     *
     * @example
     * ```
     * echo Carbon::parse('2018-07-25 12:45:16')->startOfQuarter();
     * ```
     *
     * @return static|CarbonInterface
     */
    pub