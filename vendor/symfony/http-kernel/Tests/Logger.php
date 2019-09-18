<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\ResolveInvalidReferencesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\RegisterControllerArgumentLocatorsPass;
use Symfony\Component\HttpKernel\DependencyInjection\RemoveEmptyControllerArgumentLocatorsPass;

class RemoveEmptyControllerArgumentLocatorsPassTest extends TestCase
{
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $resolver = $container->register('argument_resolver.service')->addArgument([]);

        $container->register('stdClass', 'stdClass');
        $container->register(parent::class, 'stdClass');
        $container->register('c1', RemoveTestController1::class)->addTag('controller.service_arguments');
        $container->register('c2', RemoveTestController2::class)->addTag('controller.service_arguments')
            ->addMethodCall('setTestCase', [new Reference('c1')]);

        $pass = new RegisterControllerArgumentLocatorsPass();
        $pass->process($container);

        $controllers = $container->getDefinition((string) $resolver->getArgument(0))->getArgument(0);

        $this->assertCount(2, $container->getDefinition((string) $controllers['c1::fooAction']->getValues()[0])->getArgument(0));
        $this->assertCount(1, $container->getDefinition((string) $controllers['c2::setTestCase']->getValues()[0])->getArgument(0));
        $this->assertCount(1, $container->getDefinition((string) $controllers['c2::fooAction']->getValues()[0])->getArgument(0));

        (new Re