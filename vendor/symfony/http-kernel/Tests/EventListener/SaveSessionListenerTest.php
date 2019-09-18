<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Tests\HttpCache;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\Ssi;

class SsiTest extends TestCase
{
    public function testHasSurrogateSsiCapability()
    {
        $ssi = new Ssi();

        $request = Request::create('/');
        $request->headers->set('Surrogate-Capability', 'abc="SSI/1.0"');
        $this->assertTrue($ssi->hasSurrogateCapability($request));

        $request = Request::create('/');
        $request->headers->set('Surrogate-Capability', 'foobar');
        $this->assertFalse($ssi->hasSurrogateCapability($request));

        $request = Request::create('/');
        $this->assertFalse($ssi->hasSurrogateCapability($request));
    }

    public function testAddSurrogateSsiCapability()
    {
        $ssi = new Ssi();

        $request = Request::create('/');
        $ssi->addSurrogateCapability($request);
        $this->assertEquals('symfony="SSI/1.0"', $request->headers->get('Surrogate-Capability'));

        $ssi->addSurrogateCapability($request);
        $this->assertEquals('symfony="SSI/1.0", symfony="SSI/1.0"', $request->headers->get('Surrogate-Capability'));
    }

    public function testAddSurrogateControl()
    {
        $ssi = new Ssi();

        $response = new Response('foo <!--#include virtual="" -->');
        $ssi->addSurrogateControl($response);
        $this->assertEquals('content="SSI/1.0"', $response->headers->get('Surrogate-Control'));

        $response = new Response('foo');
        $ssi->addSurrogateControl($response);
