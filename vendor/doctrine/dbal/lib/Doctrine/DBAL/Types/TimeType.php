<?php

namespace Cron;

use DateTime;
use DateTimeInterface;
use InvalidArgumentException;

/**
 * Day of week field.  Allows: * / , - ? L #
 *
 * Days of the week can be represented as a number 0-7 (0|7 = Sunday)
 * or as a three letter string: SUN, MON, TUE, WED, THU, FRI, SAT.
 *
 * 'L' stands for "last". It allows you to specify constructs such as
 * "the last Friday" of a given month.
 *
 * '#' is allowed for the day-of-week field, and must be followed by a
 * number between one and five. It allows you to specify constructs such as
 * "the second Friday" of a given month.
 */
class DayOfWeekField extends AbstractField
{
    /**
     * @inheritDoc
     */
    protected $rangeStart = 0;

    /**
     * @inheritDoc
     */
    protected $rangeEnd = 7;

    /**
     * @var array Weekday range
     */
    protected $nthRange;

    /**
     * @inheritDoc
     */
    protected $literals = [1 => 'MON', 2 => 'TUE', 3 => 'WED', 4 => 'THU', 5 => 'FRI', 6 => 'SAT', 7 => 'SUN'];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nthRange = range(1, 5);
        parent::__construct();
    }

    /**
     * @inheritDoc
     *
     * @param \DateTime|\DateTimeImmutable $date
     */
    public function isSatisfiedBy(DateTimeInterface $date, $value)
    {
        if ($value == '?') {
            return true;
        }

        // Convert text day of the week values to integers
        $value = $this->convertLiterals($value);

  