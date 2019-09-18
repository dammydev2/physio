eFromFormat('U', $currentTime->format('U'));
        } else {
            $currentDate = new DateTime($currentTime ?: 'now');
        }

        $currentDate->setTimeZone(new DateTimeZone($timeZone));
        $currentDate->setTime($currentDate->format('H'), $currentDate->format('i'), 0);
        $nextRun = clone $currentDate;
        $nth = (int) $nth;

        // We don't have to satisfy * or null fields
        $parts = array();
        $fields = array();
        foreach (self::$order as $position) {
            $part = $this->getExpression($position);
            if (null === $part || '*' === $part) {
                continue;
            }
            $parts[$position] = $part;
            $fields[$position] = $this->fieldFactory->getField($position);
        }

        // Set a hard limit to bail on an impossible date
        for ($i = 0; $i < $this->maxIterationCou