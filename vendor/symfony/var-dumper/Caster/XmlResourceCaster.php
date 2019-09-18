IntlTimeZone {
  display_name: "$expectedDisplayName"
  id: "$expectedID"
  raw_offset: $expectedRawOffset
}
EOTXT;
        $this->assertDumpEquals($expected, $var);
    }

    public function testCastIntlCalendar()
    {
        $var = \IntlCalendar::createInstance('America/Los_Angeles', 'en');

        $expectedType = $var->getType();
        $expectedFirstDayOfWeek = $var->getFirstDayOfWeek();
        $expectedMinimalDaysInFirstWeek = $var->getMinimalDaysInFirstWeek();
        $expectedRepeatedWallTimeOption = $var->getRepeatedWallTimeOption();
        $expectedSkippedWallTimeOption = $var->getSkippedWallTimeOption();
        $expectedTime = $var->getTime().'.0';
        $expectedInDaylightTime = $var->inDaylightTime() ? 'true' : 'false';
        $expectedIsLenient = $var->isLenient() ? 'true' : 'false';

        $expectedTimeZone = $var->getTimeZone();
        $expectedTimeZoneDisplayName = $expectedTimeZone->getDisplayName();
        $expectedTimeZoneID = $expectedTimeZone->getID();
        $expectedTimeZoneRawOffset = $expectedTimeZone->getRawOffset();
        $expectedTimeZoneDSTSavings = $expectedTimeZone->getDSTSavings();

        $expected = <<<EOTXT
IntlGregorianCalendar {
  type: "$expectedType"
  first_day_of_week: $expectedFirstDayOfWeek
  minimal_days_in_first_week: $expectedMinimalDaysInFirstWeek
  repeated_wall_time_option: $expectedRepeatedWallTimeOption
  skipped_wall_time_option: $expectedSkippedWallTimeOption
  time: $expectedTime
  in_daylight_time: $expectedInDaylightTime
  is_lenient: $expectedIsLenient
  time_zone: IntlTimeZone {
    display_name: "$expectedTimeZoneDisplayName"
    id: "$expectedTimeZoneID"
    raw_offset: $expectedTimeZoneRawOffset
    dst_savings: $expectedTimeZoneDSTSavings
  }
}
EOTXT;
        $this->assertDumpEquals($expected, $var);
    }

    public function testCastDateFormatter()
    {
        $var = new \IntlDateFormatter('en', \IntlDateFormatter::TRADITIONAL, \IntlDateFormatter::TRADITIONAL);

        $expectedLocale = $var->getLocale();
        $expectedPattern = $var->getPattern();
        $expectedCalendar = $var->getCalendar();
        $expectedTimeZoneId = $var->getTimeZoneId();
        $expectedTimeType = $var->getTimeType();
        $expectedDateType = $var->getDateType();

        $expectedCalendarObject = $var->getCalendarObject();
        $expectedCalendarObjectType = $expectedCalendarObject->getType();
        $expectedCalendarObjectFirstDayOfWeek = $expectedCalendarObject->getFirstDayOfWeek();
    