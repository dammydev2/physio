<?php

class Swift_Plugins_PopBeforeSmtpPluginTest extends \PHPUnit\Framework\TestCase
{
    public function testPluginConnectsToPop3HostBeforeTransportStarts()
    {
        $connection = $this->createConnection();
        $connection->expects($this->once())
                   ->method('connect');

        $plugin = $this->createPlugin('pop.host.tld', 110);
        $plugin->setConnection($connection);

        $transport = $this->createTransport();
        $evt = $this->createTransportChangeEvent($transport);

        $plugin->beforeTransportStarted($evt);
    }

    public function testPluginDisconnectsFromPop3HostBeforeTransportStarts()
    {
        $connection = $this->createConnection();
        $connection->expects($this->once())
                   ->method('disconnect');

        $plugin = $this->createPlugin('pop.host.tld', 110);
        $plugin->setConnection($connection);

        $transport = $this->createTransport();
        $evt = $this->createTransportChangeEvent($transport);

        $plugin->beforeTransportStarted($evt);
    }

    public function testPluginDoesNotConnectToSmtpIfBoundToDifferentTransport()
    {
        $connection = $this->createConnection();
        $connection->expects($this->never())
                   ->method('disconnect');
        $connection->expects($this->never())
                   ->method('connect');

        $smtp = $this->createTransport();

        $plugin = $this->createPlugin('pop.host.tld', 110);
        $plugin->setConnection($connection);
        $plugin->bindSmtp($smtp);

        $transport = $this->createTransport();
        $evt = $this->createTransportChangeEvent($transport);

        $plugin->beforeTransportStarted($evt);
    }

    public function testPluginCanBindToSpecificTransport()
    {
        $connection = $this->createConne