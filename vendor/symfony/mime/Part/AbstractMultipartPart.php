<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Mime\Tests\Header;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Header\UnstructuredHeader;

class UnstructuredHeaderTest extends TestCase
{
    private $charset = 'utf-8';

    public function testGetNameReturnsNameVerbatim()
    {
        $header = new UnstructuredHeader('Subject', '');
        $this->assertEquals('Subject', $header->getName());
    }

    public function testGetValueReturnsValueVerbatim()
    {
        $header = new UnstructuredHeader('Subject', 'Test');
        $this->assertEquals('Test', $header->getValue());
    }

    public function testBasicStructureIsKeyValuePair()
    {
        /* -- RFC 2822, 2.2
        Header fields are lines composed of a field name, followed by a colon
        (":"), followed by a field body, and terminated by CRLF.
        */
        $header = new UnstructuredHeader('Subject', 'Test');
        $this->assertEquals('Subject: Test', $header->toString());
    }

    public function testLongHeadersAreFoldedAtWordBoundary()
    {
        /* -- RFC 2822, 2.2.3
        Each header field is logically a single line of characters comprising
        the field name, the colon, and the field body.  For convenience
        however, and to deal with the 998/78 character limitations per line,
        the field body portion of a header field can be split into a multiple
        line representation; this is called "folding".  The general rule is
        that wherever this standard allows for folding white space (not
        simply WSP characters), a CRLF may be inserted before any WSP.
        */

        $value = 'The quick brown fox jumped over the fence, he was a very very '.
            'scary brown fox with a bushy tail';
        $header = new Unstructu