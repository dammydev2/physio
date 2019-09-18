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

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RedirectResponseTest extends TestCase
{
    public function testGenerateMetaRedirect()
    {
        $response = new RedirectResponse('foo.bar');

        $this->assertEquals(1, preg_match(
            '#<meta http-equiv="refresh" content="\d+;url=foo\.bar" />#',
            preg_replace(['/\s+/', '/\'/'], [' ', '"'], $response->getContent())
        ));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRedirectResponseConstructorNullUrl()
    {
        $response = new RedirectResponse(null);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRedirectResponseConstructorWrongStatusCode()
    {
        $response = new RedirectResponse('foo.bar', 404);
    }

    public function testGenerateLocationHeader()
    {
        $response = new RedirectResponse('foo.bar');

        $this->assertTrue($response->headers->has('Location'));
        $this-