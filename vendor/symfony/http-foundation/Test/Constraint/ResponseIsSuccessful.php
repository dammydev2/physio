<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation\Tests;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group time-sensitive
 */
class ResponseTest extends ResponseTestCase
{
    public function testCreate()
    {
        $response = Response::create('foo', 301, ['Foo' => 'bar']);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $response);
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals('bar', $response->headers->get('foo'));
    }

    public function testToString()
    {
        $response = new Response();
        $response = explode("\r\n", $response);
        $this->assertEquals('HTTP/1.0 200 OK', $response[0]);
        $this->assertEquals('Cache-Control: no-cache, private', $response[1]);
    }

    public function testClone()