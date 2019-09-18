<?php

namespace Cron\Tests;

use Cron\CronExpression;
use Cron\MonthField;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @author Michael Dowling <mtdowling@gmail.com>
 */
class CronExpressionTest extends TestCase
{
    /**
     * @covers \Cron\CronExpression::factory
     */
    public function testFactoryRecognizesTemplates()
    {
        $this->assertSame('0 0 1 1 *', CronExpression::factory('@annually')->getExpression());
        $this->assertSame('0 0 1 1 *', CronExpression::factory('@yearly')->getExpression());
        $this->assertSame('0 0 * * 0', CronExpression::factory('@weekly')->getExpression());
    }

    /**
     * @covers \Cron\CronExpression::__construct
     * @covers \Cron\CronExpression::getExpression
     * @covers \Cron\CronExpression::__toString
     */
    public function testParsesCronSchedule()
    {
        // '2010-09-10 12:00:00'
        $cron = CronExpression::factory('1 2-4 * 4,5,6 */3');
        $this->assertSame('1', $cron->getExpression(CronExpression::MINUTE));
        $this->assertSame('2-4', $cron->getExpression(CronExpression::HOUR));
        $this->assertSame('*', $cron->getExpression(CronExpression::DAY));
        $this->assertSame('4,5,6', $cron->getExpression(CronExpression::MONTH));
        $this->assertSame('*/3', $cron->getExpression(CronExpression::WEEKDAY));
        $this->assertSame('1 2-4 * 4,5,6 */3', $cron->getExpression());
        $this->assertSame('1 2-4 * 4,5,6 */3', (string) $cron);
        $this->assertNull($cron->getExpression('foo'));
    }

    /**
     * @covers \Cron\CronExpression::__construct
     * @covers \Cron\CronExpression::getExpression
     * @covers \Cron\CronExpression::__toString
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid CRON field value A at position 0
     */
    public function testParsesCronScheduleThrowsAnException()
    {
        CronExpression::factory('A 1 2 3 4');
    }

    /**
     * @covers \Cron\CronExpression::__construct
     * @covers \Cron\CronExpression::getExpression
     * @dataProvider scheduleWithDifferentSeparatorsProvider
     */
    public function testParsesCronScheduleWithAnySpaceCharsAsSeparators($schedule, array $expected)
    {
        $cron = CronExpression::factory($schedule);
        $this->assertSame($expected[0], $cron->getExpression(CronExpression::MINUTE));
        $this->assertSame($expected[1], $cron->getExpression(CronExpression::HOUR));
        $this->assertSame($expected[2], $cron->getExpression(CronExpression::DAY));
        $this->assertSame($expected[3], $cron->getExpression(CronExpression::MONTH));
        $this->assertSame($expected[4], $cron->getExpression(CronExpression::WEEKDAY));
    }

    /**
     * Data provider for testParsesCronScheduleWithAnySpaceCharsAsSeparators
     *
     * @return array
     */
    public static function scheduleWithDifferentSeparatorsProvider()
    {
        return array(
            array("*\t*\t*\t*\t*\t", array('*', '*', '*', '*', '*', '*')),
            array("*  *  *  *  *  ", array('*', '*', '*', '*', '*', '*')),
            array("* \t * \t * \t * \t * \t", array('*', '*', '*', '*', '*', '*')),
            array("*\t \t*\t \t*\t \t*\t \t*\t \t", array('*', '*', '*', '*', '*', '*')),
        );
    }

    /**
     * @covers \Cron\CronExpression::__construct
     * @covers \Cron\CronExpression::setExpression
     * @covers \Cron\CronExpression::setPart
     * @expectedException InvalidArgumentExcep