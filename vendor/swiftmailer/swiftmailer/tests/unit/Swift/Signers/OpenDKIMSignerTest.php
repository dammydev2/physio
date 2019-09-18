<?php

require_once dirname(__DIR__).'/EsmtpTransportTest.php';

interface Swift_Transport_EsmtpHandlerMixin extends Swift_Transport_EsmtpHandler
{
    public function setUsername($user);

    public function setPassword($pass);
}

class Swift_Transport_EsmtpTransport_ExtensionSupportTest extends Swift_Transport_EsmtpTransportTest
{
    public function testExtensionHandlersAreSortedAsNeeded()
    {
        $buf = $this->getBuffer();
        $smtp = $this->getTransport($buf);
        $ext1 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();
        $ext2 = $this->getMockery('Swift_Transport_EsmtpHandler')->shouldIgnoreMissing();

        $ext1->shouldReceive('getHandledKeyword')
             ->zeroOrMoreTimes()
             ->andReturn('AUTH');
        $ext1->shouldReceive('getPriorityOv