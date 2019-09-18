<?php

namespace Symfony\Component\HttpKernel\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ResettableServicePass;
use Symfony\Component\HttpKernel\DependencyInjection\ServicesResetter;
use Symfony\Component\HttpKernel\Tests\Fixtures\ClearableService;
use Symfony\Component\HttpKernel\Tests\Fixtures\ResettableService;

class ResettableServicePassTest extends TestCase
{
    public function testCompilerPass()
    {
        $container = new ContainerBuilder();
        $container->register('one', ResettableService::class)
            ->setPublic(true)
            ->addTag('kernel.reset', ['method' => 'reset']);
        $container->register('two', ClearableService::class)
            ->setPublic(true)
            ->addTag('kernel.reset', ['method' => 'clear']);

        $container->register('services_resetter', ServicesResetter::class)
            ->setPublic(true)
            -