<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Mime\Tests\Encoder;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Encoder\Base64Encoder;

class Base64EncoderTest extends TestCase
{
    /*
    There's really no point in testing the entire base64 encoding to the
    level QP encoding has been tested.  base64_encode() has been in PHP for
    years.
    */

    public function testInputOutputRatioIs3to4Bytes()
    {
        /*
        RFC 2045, 6.8

         The encoding process represents 24-bit groups of input bits as output
         strings of 4 encoded characters.  Proceeding from left to right, a
         24-bit input group is formed by concatenating 3 8bit input groups.
         These 24 bits are then treated as 4 concatenated 6-bit groups, each
         of which is translated into a single digit in the base64 alphabet.
         */

        $encoder = new Base64Encoder();
        $this->assertEquals('MTIz', $encoder->encodeString('123'), '3 bytes of input should yield 4 bytes of output');
        $this->assertEquals('MTIzNDU2', $encoder->encodeString('123456'), '6 bytes in input should yield 8 bytes of output');
        $this->assertEquals('MTIzNDU2Nzg5', $encoder->encodeString('123456789'), '%s: 9 b