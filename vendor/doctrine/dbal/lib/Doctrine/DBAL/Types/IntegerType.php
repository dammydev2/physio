<?php

namespace Cron;

/**
 * Abstract CRON expression field
 */
abstract class AbstractField implements FieldInterface
{
    /**
     * Full range of values that are allowed for this field type
     * @var array
     */
    protected $fullRange = [];

    /**
     * Literal values we need to convert to integers
     * @var array
     */
    protected $literals = [];

    /**
     * Start value of the full range
     * @var integer
     */
    protected $rangeStart;

    /**
     * End value of the full range
     * @var integer
     */
    protected $rangeEnd;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fullRange = range($this->rangeStart, $this->rangeEnd);
    }

    /**
     * Check to see if a field is satisfied by a value
     *
     * @param string $dateValue Date value to check
     * @param string $value