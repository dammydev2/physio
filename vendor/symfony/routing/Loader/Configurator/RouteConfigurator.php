<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Routing\Tests\Annotation;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Annotation\Route;

class RouteTest extends TestCase
{
    /**
     * @expectedException \BadMethodCallException
     */
    public function testInvalidRouteParameter()
    {
        $route = new Route(['foo' => 'bar']);
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testTryingToSetLocalesDirectly()
    {
        $route = new Route(['locales' => ['nl' => 'bar']]);
    }

    /**
     * @dataProvider getValidParameters
     */
    public function testRouteParameters($parameter, $value, $getter