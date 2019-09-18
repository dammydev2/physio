<?php

class Swift_Bug34Test extends \PHPUnit\Framework\TestCase
{
    protected function setUp()
    {
        Swift_Preferences::getInstance()->setCharset('utf-8');
    }

    public function testEmbeddedFilesWithMultipartDataCreateMultipartRelatedContentAsAnAlternative()
    {
        $message = new Swift_Message();
        $message->setCharset('utf-8');
        $message->setSubject('test subject');
        $message->addPart('plain part', 'text/plain');

        $image = new Swift_Image('<image data>', 'image.gif', 'image/gif');
        $cid = $message->embed($image);

        $message->setBody('<img src="'.$cid.'" />', 'text/html');

        $message->setTo(['user@domain.tld' => 'User']);

        $message->setFrom(['other@domain.tld' => 'Other']);
        $message->setSender(['other@domain.tld' => 'Other']);

        $id = $message->getId();
        $date = preg_quote($message->getDate()->format('r'), '~');
        $boundary = $message->getBoundary();
        $cidVal = $image->getId();

        $this->assertRegExp(
        '~^'.
  