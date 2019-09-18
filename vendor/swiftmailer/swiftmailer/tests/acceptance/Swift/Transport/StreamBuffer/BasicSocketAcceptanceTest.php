<?php

require_once dirname(dirname(dirname(__DIR__))).'/fixtures/MimeEntityFixture.php';

abstract class Swift_Mime_AbstractMimeEntityTest extends \SwiftMailerTestCase
{
    public function testGetHeadersReturnsHeaderSet()
    {
        $headers = $this->createHeaderSet();
        $entity = $this->createEntity($headers, $this->createEncoder(),
            $this->createCache()
            );
        $this->assertSame($headers, $entity->getHeaders());
    }

    public function testContentTypeIsReturnedFromHeader()
    {
        $ctype = $this->createHeader('Content-Type', 'image/jpeg-test');
        $headers = $this->createHeaderSet(['Content-Type' => $ctype]);
        $entity = $this->createEntity($headers, $this->createEncoder(),
            $this->createCache()
            );
        $this->assertEquals('image/jpeg-test', $entity->getContentType());
    }

    public function testContentTypeIsSetInHeader()
    {
        $ctype = $this->createHeader('Content-T