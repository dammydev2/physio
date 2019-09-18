<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\LazyProxy\ProxyHelper;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\TypedReference;
use Symfony\Component\HttpFoundation\Request;

/**
 * Creates the service-locators required by ServiceValueResolver.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class RegisterControllerArgumentLocatorsPass implements CompilerPassInterface
{
    private $resolverServiceId;
    private $controllerTag;
    private $controllerLocator;

    public function __construct(string $resolverServiceId = 'argument_resolver.service', string $controllerTag = 'controller.service_arguments', string $controllerLocator = 'argument_resolver.controller_locator')
    {
        $this->resolverServiceId = $resolverServiceId;
        $this->controllerTag = $controllerTag;
        $this->controllerLocator = $controllerLocator;
    }

    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition($this->resolverServiceId)) {
            return;
        }

        $parameterBag = $container->getParameterBag();
        $controllers = [];

        foreach ($container->findTaggedServiceIds($this->controllerTag, true) as $id => $tags) {
            $def = $container->getDefinition($id);
            $def->setPublic(true);
            $class = $def->getClass();
            $autowire = $def->isAutowired();
            $bindings = $def->getBindings();

            // resolve service class, taking parent definitions into account
            while ($def instanceof ChildDefinition) {
                $def = $container->findDefinition($def->getParent());
                $class = $class ?: $def->getClass();
                $bindings += $def->getBindings();
            }
            $class = $parameterBag->resolveValue($class);

            if (!$r = $container->getReflectionClass($class)) {
                throw new InvalidArgumentException(sprintf('Class "%s" used for service "%s" cannot be found.', $class, $id));
            }
            $isContainerAware = $r->implementsInterface(ContainerAwareInterface::class) || is_subclass_of($class, AbstractController::class);

            // get regular public methods
            $methods = [];
            $arguments = [];
            foreach ($r->getMethods(\ReflectionMethod::IS_PUBLIC) as $r) {
                if ('setContainer' === $r->name && $isContainerAware) {
                    continue;
                }
                if (!$r->isConstructor() && !$r->isDestructor() && !$r->isAbstract()) {
                    $methods[strtolower($r->name)] = [$r, $r->getParameters()];
                }
            }

            // validate and collect explicit per-actions and per-arguments service references
            foreach ($tags as $attributes) {
                if (!isset($attributes['action']) && !isset($attributes['argument']) && !isset($attributes['id'])) {
                    $autowire = true;
                    continue;
  