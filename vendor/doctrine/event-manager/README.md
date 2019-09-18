<?php

namespace Cron\Tests;

use Cron\DayOfWeekField;
use Cron\HoursField;
use Cron\MinutesField;
use Cron\MonthField;
use PHPUnit\Framework\TestCase;

/**
 * @author Michael Dowling <mtdowling@gmail.com>
 */
class AbstractFieldTest extends TestCase
{
    /**
     * @covers \Cron\AbstractField::isRange
     */
    public function testTestsIfRange()
    {
        $f = new DayOfWeekField();
        $this->assertTrue($f->isRange('1-2'));
        $this->assertFalse($f->isRange('2'));
    }

    /**
     * @covers \Cron\AbstractField::isIncrementsOfRanges
     */
    public function testTestsIfIncrementsOfRanges()
    {
        $f = new DayOfWeekField();
        $this->assertFalse($f->isIncrementsOfRanges('1-2'));
        $this->assertTrue($f->isIncrementsOfRanges('1/2'));
        $this->assert