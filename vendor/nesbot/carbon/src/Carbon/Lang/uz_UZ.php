i-s.u')));
            }

            return $defaults[$unit];
        };

        $year = $year === null ? $getDefault('year') : $year;
        $month = $month === null ? $getDefault('month') : $month;
        $day = $day === null ? $getDefault('day') : $day;
        $hour = $hour === null ? $getDefault('hour') : $hour;
        $minute = $minute === null ? $getDefault('minute') : $minute;
        $second = $second === null ? $getDefault('second') : $second;

        $fixYear = null;

        if ($year < 0) {
            $fixYear = $year;
            $year = 0;
        } elseif ($year > 9999) {
            $fixYear = $year - 9999;
            $year = 9999;
        }

        $second = ($second < 10 ? '0' : '').number_format($second, 6);
        /** @var CarbonImmutable|Carbon $instance */
        $instance = static::rawCreateFromFormat('!Y-n-j G:i:s.u', sprintf('%s-%s-%s %s:%02s:%02s', $year, $month, $day, $hour, $minute, $second), $tz);

        if ($fixYear !== null) {
            $instance = $instance->addYears($fixYear);
        }

        