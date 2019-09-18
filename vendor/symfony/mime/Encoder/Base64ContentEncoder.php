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
use Symfony\Component\Mime\Encoder\QpEncoder;

class QpEncoderTest extends TestCase
{
    /* -- RFC 2045, 6.7 --
    (1)   (General 8bit representation) Any octet, except a CR or
                    LF that is part of a CRLF line break of the canonical
                    (standard) form of the data being encoded, may be
                    represented by an "=" followed by a two digit
                    hexadecimal representation of the octet's value.  The
                    digits of the hexadecimal alphabet, for this purpose,
                    are "0123456789ABCDEF".  Uppercase letters must be
                    used; lowercase letters are not allowed.  Thus, for
                    example, the decimal value 12 (US-ASCII form feed) can
                    be represented by "=0C", and the decimal value 61 (US-
                    ASCII EQUAL SIGN) can be represented by "=3D".  This
                    rule must be followed except when the following rules
                    allow an alternative encoding.
                    */

    public function testPermittedCharactersAreNotEncoded()
    {
        /* -- RFC 2045