<?php

class Swift_Mime_Headers_ParameterizedHeaderTest extends \SwiftMailerTestCase
{
    private $charset = 'utf-8';
    private $lang = 'en-us';

    public function testTypeIsParameterizedHeader()
    {
        $header = $this->getHeader('Content-Type',
            $this->getHeaderEncoder('Q', true), $this->getParameterEncoder(true)
            );
        $this->assertEquals(Swift_Mime_Header::TYPE_PARAMETERIZED, $header->getFieldType());
    }

    public function testValueIsReturnedVerbatim()
    {
        $header = $this->getHeader('Content-Type',
            $this->getHeaderEncoder('Q', true), $this->getParameterEncoder(true)
            );
        $header->setValue('text/plain');
        $this->assertEquals('text/plain', $header->getValue());
    }

    public function testParametersAreAppended()
    {
        /* -- RFC 2045, 5.1
        parameter := attribute "=" value

     attribute := token
                                    ; Matching of attributes
                                    ; is ALWAYS case-insensitive.

     value := token / quoted-string

     token := 1*<any (US-ASCII) CHAR except SPACE, CTLs,
                 or tspecials>

     tspecials :=  "(" / ")" / "<" / ">" 