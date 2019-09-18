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
use Symfony\Component\Mime\Encoder\QpMimeHeaderEncoder;

class QpMimeHeaderEncoderTest extends TestCase
{
    public function testNameIsQ()
    {
        $encoder = new QpMimeHeaderEncoder();
        $this->assertEquals('Q', $encoder->getName());
    }

    public function testSpaceAndTabNeverAppear()
    {
        /* -- RFC 2047, 4.
         Only a subset of the printable 