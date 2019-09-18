<?php

use Egulias\EmailValidator\EmailValidator;

class Swift_Mime_Headers_IdentificationHeaderTest extends \PHPUnit\Framework\TestCase
{
    public function testTypeIsIdHeader()
    {
        $header = $this->getHeader('Message-ID');
        $this->assertEquals(Swift_Mime_Header::TYPE_ID, $header->getFieldType());
    }

    public function testValueMatchesMsgIdSpec()
    {
        /* -- RFC 2822, 3.6.4.
     message-id      =       "Message-ID:" msg-id CRLF

     in-reply-to     =       "In-Reply-To:" 1*msg-id CRLF

     references      =       "References:" 1*msg-id CRLF

     msg-id          =       [CFWS] "<" id-left "@" id-right ">" [CFWS]

     id-left         =       dot-atom-text / no-fold-quote / obs-id-left

     id-right        =       dot-atom-text / no-fold-literal / obs-id-right

     no-fold-quote   =       DQUOTE *(qtext / quoted-pair) DQUOTE

     no-fold-literal =       "[" *(dtext / quoted-pair) "]"
     */

        $header = $this->getHeader('Message-ID');
        $header->setId('id-left@id-right');
        $this->assertEquals('<id-left@id-right>', $header->getFieldBody());
    }

    public function testIdCanBeRetrievedVerbatim()
    {
        $header = $this->getHeader('Message-ID');
        $header->setId('id-left@id-right');
        $this->assertEquals('id-left@id-right', $header->getId());
    }

    public function testMultipleIdsCanBeSet()
    {
        $header = $this->getHeader('References');
        $header->setIds(['a@b', 'x@y']);
        $this->assertEquals(['a@b', 'x@y'], $header->getIds());
    }

    public function testSettingMultipleIdsProducesAListValue()
    {
        /* -- RFC 2822, 3.6.4.
     The "References:" and "In-Reply-To:" field each contain one or more
     unique message identifiers, optionally separated by CFWS.

     .. SNIP ..

     in-reply-to     =       "In-Reply-To:" 1*msg-id CRLF

     references      =       "References:" 1*msg-id CRLF
     */

        $header = $this->getHeader('References');
        $header->setIds(['a@b', 'x@y']);
        $this->assertEquals('<a@b> <x@y>', $header->getFieldBody());
    }

    public function testIdLeftCanBeQuoted()
    {
        /* -- RFC 2822, 3.6.4.
     id-left         =       dot-atom-text / no-fold-quote / obs-id-left
     */

        $header = $this->getHeader('References');
        $header->setId('"ab"@c');
        $this->assertEquals('"ab"@c', $header->getId());
        $this->assertEquals('<"ab"@c>', $header->getFieldBody());
    }

    public function testIdLeftCanContainAnglesAsQuotedPairs()
    {
        /* -- RFC 2822, 3.6.4.
     no-fold-quote   =       DQUOTE *(qtext / quoted-pair) DQUOTE
     */

        $header = $this->getHeader('References');
        $header->setId('"a\\<\\>b"@c');
        $this->assertEquals('"a\\<\\>b"@c', $header->getId());
        $this->assertEquals('<"a\\<\\>b"@c>', $header->getFieldBody());
    }

    public function testIdLeftCanBeDotAtom(