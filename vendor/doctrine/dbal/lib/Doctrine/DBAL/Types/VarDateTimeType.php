<?php

namespace Cron;

use DateTimeInterface;
use DateTimeZone;

/**
 * Hours field.  Allows: * , / -
 */
class HoursField extends AbstractField
{
    /**
     * @inheritDoc
     */
    protected $rangeStart = 0;

    /**
     * @inheritDoc
     */
    protected $rangeEnd = 23;

    /**
     * @inheritDoc
     */
    public function isSatisfiedBy(DateTimeInterface $date, $value)
    {
        if ($value == '?') {
            return true;
        }

        return $this->isSatisfied($date->format('H'), $value);
    }

    /**
     * {@inheritDoc}
     *
     * @param \DateTime|\DateTimeImmutable &$date
     * @param string|null                  $parts
     */
    public function increment(DateTimeInterface &$date, $invert = false, $parts = null)
    {
        // Change timezone to UTC temporarily. This will
        // allow us to go back or forwards and hour even
        // if DS