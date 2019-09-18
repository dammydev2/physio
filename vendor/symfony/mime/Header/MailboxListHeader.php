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
use Symfony\Component\Mime\Header\ParameterizedHeader;

class ParameterizedHeaderTest extends TestCase
{
    private $charset = 'utf-8';
    private $lang = 'en-us';

    public function testValueIsReturnedVerbatim()
    {
        $header = new ParameterizedHeader('Content-Type', 'text/plain');
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

        tspecials :=  "(" / ")" / "<" / ">" / "@" /
                   "," / ";" / ":" / "\" / <">
                   "/" / "[" / "]" / "?" / "="
                   ; Must be in quoted-string,
                   ; to use within parameter values
        */

        $header = new ParameterizedHeader('Content-Type', 'text/plain');
        $header->setParameters(['charset' => 'utf-8']);
        $this->assertEquals('text/plain; charset=utf-8', $header->getBodyAsString());
    }

    public function testSpaceInParamResultsInQuotedString()
    {
        $header = new ParameterizedHeader('Content-Type', 'attachment');
        $header->setParameters(['filename' => 'my file.txt']);
        $this->assertEquals('attachment; filename="my file.txt"', $header->getBodyAsString());
    }

    public function testLongParamsAreBrokenIntoMultipleAttributeStrings()
    {
        /* -- RFC 2231, 3.
        The asterisk character ("*") followed
        by a decimal count is employed to indicate that multiple parameters
        are being used to encapsulate a single parameter value.  The count
        starts at 0 and increments by 1 for each subsequent section of the
        parameter value.  Decimal values are used and neither leading zeroes
        nor gaps in the sequence are allowed.

        The original parameter value is recovered by concatenating the
        various sections of the parameter, in order.  For example, the
        content-type field

                Content-Type: message/external-body; access-type=URL;
         URL*0="ftp://";
         URL*1="cs.utk.edu/pub/moore/bulk-mailer/bulk-mailer.tar"

        is semantically identical to

                Content-Type: message/external-body; access-type=URL;
                    URL="ftp://cs.utk.edu/pub/moore/bulk-mailer/bulk-mailer.tar"

        Note that quotes around parameter values are part of the value
        syntax; they are NOT part of the value itself.  Furthermore, it is
        explicitly permitted to have a mixture of quoted and unquoted
        continuation fields.
        */

        $value = str_repeat('a', 180);

        $header = new ParameterizedHeader('Content-Disposition', 'attachment');
        $header->setParameters(['filename' => $valu