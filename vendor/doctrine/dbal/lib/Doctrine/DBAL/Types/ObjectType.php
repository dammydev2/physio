   $this->setPart($position, $part);
        }

        return $this;
    }

    /**
     * Set part of the CRON expression
     *
     * @param int    $position The position of the CRON expression to set
     * @param string $value    The value to set
     *
     * @return CronExpression
     * @throws \InvalidArgumentException if the value is not valid for the part
     */
    public function setPart($position, $value)
    {
        if (!$this->fieldFactory->getField($position)->validate($value)) {
            throw new InvalidArgumentException(
                'Invalid CRON field value ' . $value . ' at position ' . $position
            );
        }

        $this->cronParts[$position] = $value;

        return $this;
    }

    /**
     * Set max iteration count for searching next run dates
     *
     * @param int $maxIterationCount Max iteration count when searching for next run date
     *
     * @return CronExpression
     */
    public function setMaxIterationCount($maxIterationCount)
    {
        $this->maxIterationCount = $maxIterationCount;

        return $this;
    }

    /**
     * Get a next run date relative to the current date or a specific date
     *
     * @param string|\DateTimeInterface $currentTime      Relative calculation date
     * @param int                       $nth              Number of matches to skip before returning a
     *                                                    matching next run date.  0, the default, will return the
     *                                                    current 