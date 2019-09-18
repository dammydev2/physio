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
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Header\PathHeader;

class PathHeaderTest extends TestCase
{
    public function testSingleAddressCanBeSetAndFetched()
    {
        $header = new PathHeader('Return-Path', $address = new Address('chris@swiftmailer.org'));
        $this->assertEquals($address, $header->getAddress());
    }

    /**
     * @expectedException \Exception
     */
    public function testAddressMustComplyWithRfc2822()
    {
        $header = new PathHeader('Return-Path', new Address('chr is@swiftmailer.org'));
    }

    public function testValueIsAngleAddrWithValidAddress()
    {
        /* -- RFC 2822, 3.6.7.

            return          =       "Return-Path:" path CRLF

            path            =       ([CFWS] "<" ([CFWS] / addr-spec) ">" [CFWS]) /
                                                            obs-path
         */

        $header = new PathHeader('Return-Path', new Address('chris@swiftmailer.org'));
        $this->asse