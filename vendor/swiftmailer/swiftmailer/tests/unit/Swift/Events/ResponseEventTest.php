<?php

class Swift_Mime_Headers_DateHeaderTest extends \PHPUnit\Framework\TestCase
{
    /* --
    The following tests refer to RFC 2822, section 3.6.1 and 3.3.
    */

    public function testTypeIsDateHeader()
    {
        $header = $this->getHeader('Date');
        $this->assertEquals(Swift_Mime_Header::TYPE_DATE, $header->getFieldType());
    }

    public function testGetDateTime()
    {
        $dateTime = new DateTimeImmutable();
        $header = $this->getHeader('Date');
        $header->setDateTime($dateTime);
        $this->assertSame($dateTime, $header->getDateTime());
    }

    public function testDateTimeCanBeSetBySetter()
    {
        $dateTime = new DateTimeImmutable();
        $header = $this->getHeader('Date');
        $header->setDateTime($dateTime);
        $this->assertSame($dateTime, $header->getDateTime());
    }

    public function testDateTimeIsConvertedToImmutable()
    {
        $dateTime = new DateTime();
        $header = $this->getHeader('Date');
        $header->setDateTime($dateTime);
        $this->assertInstanceOf('DateTimeImmutable', $header->getDateTime());
        $this->assertEquals($dateTime->getTimestamp(), $header-