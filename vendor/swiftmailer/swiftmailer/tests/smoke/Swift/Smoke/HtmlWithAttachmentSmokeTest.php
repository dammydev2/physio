<?php

class Swift_Mime_SimpleHeaderSetTest extends \PHPUnit\Framework\TestCase
{
    public function testAddMailboxHeaderDelegatesToFactory()
    {
        $factory = $this->createFactory();
        $factory->expects($this->once())
                ->method('createMailboxHeader')
                ->with('From', ['person@domain' => 'Person'])
                ->will($this->returnValue($this->createHeader('From')));

        $set = $this->createSet($factory);
        $set->addMailboxHeader('From', ['person@domain' => 'Person']);
    }

    public function testAddDateHeaderDelegatesToFactory()
    {
        $dateTime = new DateTimeImmutable();

        $factory = $this->createFactory();
        $factory->expects($this->once())
                ->method('createDateHeader')
                ->with('Date', $dateTime)
                ->will($this->returnValue($this->createHeader('Date')));

        $set = $this->createSet($factory);
        $set->addDateHeader('Date', $dateTime);
    }

    public function testAddTextHeaderDelegatesToFactory()
    {
        $factory = $this->createFactory();
        $facto