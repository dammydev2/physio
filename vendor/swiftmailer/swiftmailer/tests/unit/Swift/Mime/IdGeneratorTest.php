<?php

class Swift_Plugins_RedirectingPluginTest extends \PHPUnit\Framework\TestCase
{
    public function testRecipientCanBeSetAndFetched()
    {
        $plugin = new Swift_Plugins_RedirectingPlugin('fabien@example.com');
        $this->assertEquals('fabien@example.com', $plugin->getRecipient());
        $plugin->setRecipient('chris@example.com');
        $this->assertEquals('chris@example.com', $plugin->getRecipient());
    }

    public function testPluginChangesRecipients()
    {
        $message = (new Swift_Message())
            ->setSubject('...')
            ->setFrom(['john@example.com' => 'John Doe'])
            ->setTo($to = [
                'fabien-to@example.com' => 'Fabien (To)',
                'chris-to@example.com' => 'Chris (To)',
            ])
            ->setCc($cc = [
                'fabien-cc@example.com' => 'Fabien (Cc)',
                'chris-cc@example.com' => 'Chris (Cc)',
            ])
            ->setBcc($bcc = [
                'fabien-bcc@example.com' => '