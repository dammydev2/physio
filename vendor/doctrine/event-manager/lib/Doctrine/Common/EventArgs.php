uleProvider()
    {
        return array(
            array('*/2 */2 * * *', '2015-08-10 21:47:27', '2015-08-10 22:00:00', false),
            array('* * * * *', '2015-08-10 21:50:37', '2015-08-10 21:50:00', true),
            array('* 20,21,22 * * *', '2015-08-10 21:50:00', '2015-08-10 21:50:00', true),
            // Handles CSV values
            array('* 20,22 * * *', '2015-08-10 21:50:00', '2015-08-10 22:00:00', false),
            // CSV values can be complex
            array('7-9 * */9 * *', '2015-08-10 22:02:33', '2015-08-10 22:07:00', false),
            // 15th minute, of the second hour, every 15 days, in January, every Friday
            array('1 * * * 7', '2015-08-10 21:47:27', '2015-08-16 00:01:00', false),
            // Test with exact times
            array('47 21 * * *', strtotime('2015-08-10 21:47:30'), '2015-08-10 21:47:00', true),
            // Test Day of the week (issue #1)
            // According cron implementation, 0|7 = sunday, 1 => monday, etc
            array('* * * * 0', strtotime('2011-06-15 23:09:00'), '2011-06-19 00:00:00', false),
            array('* * * * 7', strtotime('2011-06-15 23:09:00'), '2011-06-19 00:00:00', false),
            array('* * * * 1', strtotime('2011-06-15 23:09:00'), '2011-06-20 00:00:00', false),
            // Should return the sunday date a