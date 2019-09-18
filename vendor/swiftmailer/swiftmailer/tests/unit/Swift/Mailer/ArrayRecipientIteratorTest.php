<?php

use Egulias\EmailValidator\EmailValidator;

class Swift_Mime_Headers_PathHeaderTest extends \PHPUnit\Framework\TestCase
{
    public function testTypeIsPathHeader()
    {
        $header = $this->getHeader('Return-Path');
        $this->assertEquals(Swift_Mime_Header::TYPE_PATH, $header->getFieldType());
    }

    public function testSingleAddressCanBeSetAndFetched()
    {
        $header = $this->getHeader('Return-Path');
        $header->setAddress('chris@swiftmailer.org');
        $this->assertEquals('chris@swiftmailer.org', $header->getAddress());
    }

    /**
     * @expectedException \Exception
     */
    public function testAddressMustComplyWithRfc2822()
    {
        $header = $this->getHeader('Return-Path');
        $header->setAddress('chr is@swiftmailer.org');
    }

    public function testValueIsAngleAddrWithValidAddress()
    {
        /* -- RFC 2822, 3.6.7.

            return          =       "Return-Path:" path CRLF

            path            =       ([CFWS] "<" ([CFWS] / addr-spec) ">" [CFWS]) /
                                                            obs-path
     */

        $header = $this->getHeader('Return-Path');
        $header->setAddress('chris@swiftmailer.org');
        $this->assertEquals('<chris@swiftmailer.org>', $header->getFieldBody());
    }

    public function testAddressIsIdnEncoded()
    {
        $header = $this->getHeader('Return-Path');
        $header->setAddress