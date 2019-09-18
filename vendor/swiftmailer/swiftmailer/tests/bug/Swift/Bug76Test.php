<?php

use Egulias\EmailValidator\EmailValidator;

class Swift_Mime_SimpleHeaderFactoryTest extends \PHPUnit\Framework\TestCase
{
    private $factory;

    protected function setUp()
    {
        $this->factory = $this->createFactory();
    }

    public function testMailboxHeaderIsCorrectType()
    {
        $header = $this->factory->createMailboxHeader('X-Foo');
        $this->assertInstanceOf('Swift_Mime_Headers_MailboxHeader', $header);
    }

    public function testMailboxHeaderHasCorrectName()
    {
        $header = $this->factory->createMailboxHeader('X-Foo');
        $this->assertEquals('X-Foo', $header->getFieldName());
    }

    public function testMailboxHeaderHasCorrectModel()
    {
        $header = $this->factory->createMailboxHeader('X-Foo',
            ['foo@bar' => 'FooBar']
            );
        $this->assertEquals(['foo@bar' => 'FooBar'], $header->getFieldBodyModel());
    }

    public function testDateHeaderHasCorrectType()
    {
        $header = $this->factory->createDateHeader('X-Date');
        $this->assertInstanceOf('Swift_Mime_Headers_DateHeader', $header);
    }

    public function testDateHeaderHasCorrectName()
    {
        $header = $this->factory->createDateHeader('X-Date');
        $this->assertEquals('X-Date', $header->getFieldName());
    }

    public function testDateHeaderHasCorrectModel()
    {
        $dateTime = new \DateTimeImmutable();
        $header = $this->factory->createDateHeader('X-Date', $dateTime);
        $this->assertEquals($dateTime, $header->getFieldBodyModel());
    }

    public function testTextHeaderHasCorrectType()
    {
        $header = $this->factory->createTextHeader('X-Foo');
        $this->assertInstanceOf('Swift_Mime_Headers_UnstructuredHeader', $header);
    }

    public function testTextHeaderHasCorrectName()
    {
        $header = $this->factory->createTextHeader('X-Foo');
        $this->assertEquals('X-Foo', $header->getFieldName());
    }

    public f