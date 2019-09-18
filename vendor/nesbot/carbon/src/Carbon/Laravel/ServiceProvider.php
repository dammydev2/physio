        'D' => 'day',
                'DD' => ['rawFormat', ['d']],
                'Do' => ['ordinal', ['day', 'D']],
                'd' => 'dayOfWeek',
                'dd' => function (CarbonInterface $date, $originalFormat = null) {
                    return $date->getTranslatedMinDayName($originalFormat);
                },
                'ddd' => function (CarbonInterface $date, $originalFormat = null) {
                    return $date->getTranslatedShortDayName($originalFormat);
                },
                'dddd' => function (CarbonInterface $date, $originalFormat = null) {
                    return $date->getTranslatedDayName($originalFormat);
                },
                'DDD' => 'dayOfYear',
                'DDDD' => ['getPaddedUnit', ['dayOfYear', 3]],
                'DDDo' => ['ordinal', ['dayOfYear', 'DDD']],
                'e' => ['weekday', []],
                'E' => 'dayOfWeekIso',
                'H' => ['rawFormat', ['G']],
                'HH' => ['rawFormat', ['H']],
                'h' => ['rawFormat', ['g']],
                'hh' => ['rawFormat', ['h']],
                'k' => 'noZeroHour',
                'kk' => ['getPaddedUnit', ['noZeroHour']],
                'hmm' => ['rawFormat', 