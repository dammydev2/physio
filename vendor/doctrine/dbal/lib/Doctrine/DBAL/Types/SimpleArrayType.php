cron expression
     * @param null|string               $timeZone         TimeZone to use instead of the system default
     *
     * @return \DateTime[] Returns an array of run dates
     */
    public function getMultipleRunDates($total, $currentTime = 'now', $invert = false, $allowCurrentDate = false, $timeZone = null)
    {
        $matches = array();
        for ($i = 0; $i < max(0, $total); $i++) {
            try {
                $matches[] = $this->getRunDate($currentTime, $i, $invert, $allowCurrentDate, $timeZone);
            } catch (RuntimeException $e) {
                break;
            }
        }

        return $matches;
    }

    /**
     * Get all or part of the CRON expression
     *
     * @param string $part Specify the part to retrieve or NULL to get the full
     *                     cron schedule string.
     *
     * @return string|null Returns the CRON expression, a part of the
     *                     CRON expression, or NULL if the part was specified but not found
     */
    public function getExpression($part = null)
    {
        if (null === $part) {
            return implode(' ', $this->cronParts);
        } elseif (array_key_exists($part, $this->cronParts)) {
            return $this->cronParts[$part];
        }

        return null;
    }

    /**
     * Helper method to output the full expression.
    