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
use Symfony\Component\Mime\Encoder\Rfc2231Encoder;

class Rfc2231EncoderTest extends TestCase
{
    private $rfc2045Token = '/^[\x21\x23-\x27\x2A\x2B\x2D\x2E\x30-\x39\x41-\x5A\x5E-\x7E]+$/D';

    /* --
    This algorithm is described in RFC 2231, but is barely touched upon except
    for mentioning bytes can be represented as their octet values (e.g. %20 for
    the SPACE character).

    The tests here focus on how to use that representation to always generate text
    which matches RFC 2045's definition of "token".
    */

    public