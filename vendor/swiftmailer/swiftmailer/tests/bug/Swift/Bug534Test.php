<?php


class Swift_Mime_MimePartTest extends Swift_Mime_AbstractMimeEntityTest
{
    public function testNestingLevelIsSubpart()
    {
        $part = $this->createMimePart($this->createHeaderSet(),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals(
            Swift_Mime_SimpleMimeEntity::LEVEL_ALTERNATIVE, $part->getNestingLevel()
            );
    }

    public function testCharsetIsReturnedFromHeader()
    {
        /* -- RFC 2046, 4.1.2.
        A critical parameter that may be specified in the Content-Type field
        for "text/plain" data is the character set.  This is specified with a
        "charset" parameter, as in:

     Content-type: text/plain; charset=iso-8859-1

        Unlike some other parameter values, the values of the charset
        parameter are NOT case sensitive.  The default character set, which
        must be assumed in the absence of a charset parameter, is US-ASCII.
        */

        $cType = $this->createHeader('Content-Type', 'text/plain',
            ['charset' => 'iso-8859-1']
            );
        $part = $this->createMimePart($this->createHeaderSet([
            'Content-Type' => $cType, ]),
            $this->createEncoder(), $this->createCache()
            );
        $this->assertEquals('iso-8859-1', $part->getCharset());
    }

    public function