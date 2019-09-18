<?php

namespace Cron;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use InvalidArgumentException;
use RuntimeException;

/**
 * CRON expression parser that can determine whether or not a CRON expression is
 * due to run, the next run date and previous run date of a CRON expression.
 * The determinations made by this class are accurate if checked run once per
 * minute (seconds are dropped from date time comparisons).
 *
 * Schedule parts must map to:
 * minute [0-59], hour [0-23], day of month, month [1-12|JAN-DEC], day of week
 * [1-7|MON-SUN], and an optional year.
 *
 * @link http://en.wikipedia.org/wiki/Cron
 */
class CronExpression
{
    const MINUTE = 0;
    const HOUR = 1;
    const DAY = 2;
    const MONTH = 3;
    const WEEKDAY = 4;
    const YEAR = 5;

    /**
     * @var array CRON expression parts
     */
    private $cronParts;

    /**
     * @var FieldFactory CRON field factory
     */
    private $fieldFactory;

    /**
     * @var int Max iteration count when searching for next run date
     */
    private $maxIterationCount = 1000;

    /**
     * @var array Order in which to test of cron parts
     */
    private static $order = array(self::YEAR, self::MONTH, self::DAY, self::WEEKDAY, self::HOUR, self::MINUTE);

    /**
     * Factory method to create a new CronExpression.
     *
     * @param string $expression The CRON expression to create.  There are
     *                           several special predefined values which can be used to substitute the
     *                           CRON expression:
     *
     *      `@yearly`, `@annually` - Run once a year, midnight, Jan. 1 - 0 0 1 1 *
     *      `@monthly` - Run once a month, midnight, first of month - 0 0 1 * *
     *      `@weekly` - Run once a week,