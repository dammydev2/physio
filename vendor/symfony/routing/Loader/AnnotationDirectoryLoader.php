<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Routing\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouteCollectionBuilder;

class RouteCollectionBuilderTest extends TestCase
{
    public function testImport()
    {
        $resolvedLoader = $this->getMockBuilder('Symfony\Component\Config\Loader\LoaderInterface')->getMock();
        $resolver = $this->getMockBuilder('Symfony\Component\Config\Loader\LoaderResolverInterface')->getMock();
        $resolver->expects($this->once())
            ->method('resolve')
            ->with('admin_routing.yml', 'yaml')
            ->will($this->returnValue($resolvedLoader));

        $originalRoute = new Route('/foo/path');
        $expectedCollection = new RouteCollection();
        $expectedCollection->add('one_test_route', $originalRoute);
        $expectedCollection->addResource(new FileResource(__DIR__.'/Fixtures/file_resource.yml'));

        $resolvedLoader
            ->expects($this->once())
            ->method('load')
            ->with('admin_routing.yml', 'yaml')
            ->will($this->returnValue($expectedCollection));

        $loader = $this->getMockBuilder('Symfony\Component\Config\Loader\LoaderInterface')->getMock();
        $loader->expects($this->any())
            ->method('getResolver')
            ->will($this->returnValue($resolver));

        // import the file!
        $routes = new RouteCollectionBuilder($loader);
        $importedRoutes = $routes->import('admin_routing.yml', '/', 'yaml');

        // we should get back a RouteCollectionBuilder
        $this->assertInstanceOf('Symfony\Component\Routing\RouteCollectionBuilder', $importedRoutes);

        // get the collection back so we can look at it
        $addedCollection = $importedRoutes->build();
        $route = $addedCollection->get('one_test_route');
        $this->assertSame($originalRoute, $route);
        // should return file_resource.yml, which is in the original collection
        $this->assertCount(1, $addedCollection->getResources());

        // make sure the routes were imported into the top-level builder
        $routeCollection = $routes->build();
        $this->assertCount(1, $routes->build());
        $this->assertCount(1, $routeCollection->getResources());
    }

    public function testImportAddResources()
    {
        $routeCollectionBuilder = new RouteCollectionB