 type 1 (UTC offset)
            ['-12:00', '-12:00', ''],
            ['+00:00', '+00:00', ''],
            ['+14:00', '+14:00', ''],

            // type 2 (timezone abbreviation)
            ['GMT', '+00:00', ''],
            ['a', '+01:00', ''],
            ['b', '+02:00', ''],
            ['z', '+00:00', ''],

            // type 3 (timezone identifier)
            ['Africa/Tunis', 'Africa/Tunis (%s:00)', $xRegion],
            ['America/Panama', 'America/Panama (%s:00)', $xRegion],
            ['Asia/Jerusalem', 'Asia/Jerusalem (%s:00)', $xRegion],
            ['Atlantic/Canary', 'Atlantic/Canary (%s:00)', $xRegion],
            ['Australia/Perth', 'Australia/Perth (%s:00)', $xRegion],
            ['Europe/Zurich', 'Europe/Zurich (%s:00)', $xRegion],
            ['Pacific/Tahiti', 'Pacific/Tahiti (%s:00)', $xRegion],
        ];
    }

    /**
     * @dataProvider providePeriods
     */
    public function testDumpPeriod($start, $interval, $end, $options, $expected)
    {
        $p = new \DatePeriod(new \DateTime($start), new \DateInterval($interval), \is_int($end) ? $end : new \DateTime($end), $options);

        $xDump = <<<EODUMP
DatePeriod {
  period: $expected
%A}
EODUMP;

        $this->assertDumpMatchesFormat($xDump, $p);
    }

    /**
     * @dataProvider providePeriods
     */
    public function testCastPeriod($start, $interval, $end, $options, $xPeriod, $xDates)
    {
        $p = new \DatePeriod(new \DateTime($start), new \DateInterval($interval), \is_int($end) ? $end : new \DateTime($end), $options);
        $stub = new Stub();

        $cast = DateCaster::castPeriod($p, [], $stub, false, 0);

        $xDump = <<<EODUMP
array:1 [
  "\\x00~\\x00period" => $xPeriod
]
EODUMP;

        $this->assertDumpEquals($xDump, $cast);

        $xDump = <<<EODUMP
Symfony\Component\VarDump