<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Tests\Fragment;

use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpKernel\Fragment\InlineFragmentRenderer;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\KernelEvents;

class InlineFragmentRendererTest extends TestCase
{
    public function testRender()
    {
        $strategy = new InlineFragmentRenderer($this->getKernel($this->returnValue(new Response('foo'))));

        $this->assertEquals('foo', $strategy->render('/', Request::create('/'))->getContent());
    }

    public function testRenderWithControllerReference()
    {
        $strategy = new InlineFragmentRenderer($this->getKernel($this->returnValue(new Response('foo'))));

        $this->assertEquals('foo', $strategy->render(new ControllerReference('main_controller', [], []), Request::create('/'))->getContent());
    }

    public function testRenderWithObjectsAsAttributes()
    {
        $object = new \stdClass();

        $subRequest = Request::create('/_fragment?_path=_format%3Dhtml%26_locale%3Den%26_controller%3Dmain_controller');
        $subRe