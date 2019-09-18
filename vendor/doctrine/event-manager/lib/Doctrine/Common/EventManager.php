00:00'), '2011-07-29 00:00:00', false),
            // Test the last weekday of a month
            array('* * * * 5L', strtotime('2011-07-01 00:00:00'), '2011-07-29 00:00:00', false),
            array('* * * * 6L', strtotime('2011-07-01 00:00:00'), '2011-07-30 00:00:00', false),
            array('* * * * 7L', strtotime('2011-07-01 00:00:00'), '2011-07-31 00:00:00', false),
            array('* * * * 1L', strtotime('2011-07-24 00:00:00'), '2011-07-25 00:00:00', false),
            array('* * * 1 5L', strtotime('2011-12-25 00:00:00'), '2012-01-27 00:00:00', false),
            // Test the hash symbol for the nth weekday of a given month
            array('* * * * 5#2', strtotime('2011-07-01 00:00:00'), '2011-07-08 00:00:00', false),
            array('* * * * 5#1', strtotime('2011-07-01 00:00:00'), '2011-07-01 00:00:00', true),
            array('* * * * 3#4', strtotime('2011-07-01 00:00:00'), '2011-07-27 00:00:00', false),

            // Issue #7, documented example failed
            ['3-59/15 6-12 */15 1 2-5', strtotime('2017-01-08 00:00:00'), '2017-01-31 06:03:00', false],

            // https://github.com/laravel/framework/commit/07d160ac3cc9764d5b429734ffce4fa311385403
            ['* * * * MON-FRI', strtotime('2017-01-08 00:00:00'), strtotime('2017-01-09 00:00:00'), false],
            ['* * * * TUE', strtotime('2017-01-08 00:00:00'), strtotime('2017-01-10 00:00:00'), false],
        );
    }

    /**
     * @covers \Cron\CronExpression::isDue
     * @covers \Cron\CronExpression::getNextRunDate
     * @covers \Cron\DayOfMonthField
     * @covers \Cron\DayOfWeekField
     * @covers \Cron\MinutesField
     * @covers \Cron\HoursField
     * @covers \Cron\MonthField
     * @covers \Cron\CronExpression::getRunDate
     * @dataProvider scheduleProvider
     */
    public function testDeterminesIfCronIsDue($schedule, $relativeTime, $nextRun, $isDue)
    {
        $relativeTimeString = is_int($relativeTime) ? date('Y-m-d H:i:s', $relativeTime) : $relativeTime;

        // Test next run date
        $cron = CronExpression::factory($schedule);
        if (is_string($relativeTime)) {
            $relativeTime = new DateTime($relativeTime);
        } elseif (is_int($relativeTime)) {
            $relativeTime = date('Y-m-d H:i:s', $relativeTime);
        }

        if (is_string($nextRun)) {
            $nextRunDate = new DateTime($nextRun);
        } elseif (is_int($nextRun)) {
            $nextRunDate = new DateTime();
            $nextRunDate->setTimestamp($nextRun);
        }
        $this->assertSame($isDue, $cron->isDue($relativeTime));
        $next = $cron->getNextRunDate($relativeTime, 0, true);

        $this->assertEquals($nextRunDate, $next);
    }

    /**
     * @covers \Cron\CronExpression::isDue
     */
    public function testIsDueHandlesDifferentDates()
    {
        $cron = CronExpression::factory('* * * * *');
        $this->assertTrue($cron->isDue());
        $this->assertTrue($cron->isDue('now'));
        $this->assertTrue($cron->isDue(new DateTime('now')));
        $this->assertTrue($cron->isDue(date('Y-m-d H:i')));
        $this->assertTrue($cron->isDue(new DateTimeImmutable('now')));
    }

    /**
     * @covers \Cron\CronExpression::isDue
     */
    public function testIsDueHandlesDifferentDefaultTimezones()
    {
        $originalTimezone = date_default_timezone_get();
        $cron = CronExpression::factory('0 15 * * 3'); //Wednesday at 15:00
        $date = '2014-01-01 15:00'; //Wednesday

        date_default_timezone_set('UTC');
        $this->assertTrue($cron->isDue(new DateTime($date), 'UTC'));
        $this->assertFalse($cron->isDue(new DateTime($date), 'Europe/Amsterdam'));
        $this->assertFalse($cron->isDue(new DateTime($date), 'Asia/Tokyo'));

        date_default_timezone_set('Europe/Amsterdam');
        $this->assertFalse($cron->isDue(new DateTime($date), 'UTC'));
        $this->assertTrue($cron->isDue(new DateTime($date), 'Europe/Amsterdam'));
   