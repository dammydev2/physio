<?php

class Swift_MessageTest extends \PHPUnit\Framework\TestCase
{
    public function testCloning()
    {
        $message1 = new Swift_Message('subj', 'body', 'ctype');
        $message2 = new Swift_Message('subj', 'body', 'ctype');
        $message1_clone = clone $message1;

        $this->recursiveObjectCloningCheck($message1, $message2, $message1_clone);
        // the test above will fail if the two messages are not identical
        $this->addToAssertionCount(1);
    }

    public function testCloningWithSigners()
    {
        $message1 = new Swift_Message('subj', 'body', 'ctype');
        $signer = new Swift_Signers_DKIMSigner(dirname(dirname(__DIR__)).'/_samples/dkim/dkim.test.priv', 'test.example', 'example');
        $message1->attachSigner($signer);
        $message2 = new Swift_Message('subj', 'body', 'ctype');
        $signer = new Swift_Signers_DKIMSigner(dirname(dirname(__DIR__)).'/_samples/dkim/dkim.test.priv', 'test.example', 'example');
        $message2->attachSigner($signer);
        $message1_clone = clone $message1;

        $this->recursiveObjectCloningCheck($message1, $message2, $message1_clone);
        // the test above will fail if the two messages are not identical
        $this->addToAssertionCount(1);
    }

    public function testBodySwap()
    {
        $message1 = new Swift_Message('Test');
        $html = new Swift_MimePart('<html></html>', 'text/html');
        $html->getHeaders()->addTextH