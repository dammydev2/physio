<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation\Tests\Test\Constraint;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestFailure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseHeaderSame;

class ResponseHeaderSameTest extends TestCase
{
    public function testConstraint(): void
    {
        $constraint = new ResponseHeaderSame('Cache-Control', 'no-cache, private');
        $this->assertTrue($constraint->evaluate(new Response(), '', true));
        $constraint = new Respon