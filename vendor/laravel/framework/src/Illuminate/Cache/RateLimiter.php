<?php

namespace Illuminate\Container;

use Closure;
use Exception;
use ArrayAccess;
use LogicException;
use ReflectionClass;
use ReflectionParameter;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container as ContainerContract;

class Container implements ArrayAccess, ContainerContract
{
    /**
     * The current globally available container (if any).
     *
     * @var static
     */
    protected static $instance;

    /**
     * An array of the types that have been resolved.
     *
     * @var bool[]
     */
    protected $resolved = [];

    /**
     * The container's bindings.
     *
     * @var array[]
     */
    protected $bindings = [];

    /**
     * The container's method bindings.
     *
     * @var \Closure[]
     */
    protected $methodBindings = [];

    /**
     * The container's shared instances.
     *
     * @var object[]
     */
    protected $instances = [];

    /**
     * The registered type aliases.
     *
     * @var string[]
     */
    protected $aliases = [];

    /**
     * The registered aliases keyed by the abstract name.
     *
     * @var array[]
     */
    protected $abstractAliases = [];

    /**
     * The extension closures for services.
     *
     * @var array[]
     */
    protected $extenders = [];

    /**
     * All of the registered tags.
     *
     * @var array[]
     */
    protected $tags = [];

    /**
     * The stack of concretions currently being built.
     *
     * @var array[]
     */
    protected $buildStack = [];

    /**
     * The parameter override stack.
     *
     * @var array[]
     */
    protected $with = [];

    /**
     * The contextual binding map.
     *
     * @var array[]
     */
    public $contextual = [];

    /**
     * All of the registered rebound callbacks.
     *
     * @var array[]
     */
    protected $reboundCallbacks = [];

    /**
     * All of the global resolving callbacks.
     *
     * @var \Closure[]
     */
    protected $globalResolvingCallbacks = [];

    /**
     * All of the global after resolving callbacks.
     *
     * @var \Closure[]
     */
    protected $globalAfterResolvingCallbacks = [];

    /**
     * All of the resolving callbacks by class type.
     *
     * @var array[]
     */
    protected $resolvingCallbacks = [];

    /**
     * All of the after resolving callbacks by class type.
     *
     * @var array[]
     */
    protected $afterResolvingCallbacks = [];

    /**
     * Define a contextual binding.
     *
     * @param  array|string  $concrete
     * @return \Illuminate\Contracts\Container\ContextualBindingBuilder
     */
    public function when($concrete)
    {
        $aliases = [];

        foreach (Arr::wrap($concrete) as $c) {
            $aliases[] = $this->getAlias($c);
        }

        return new ContextualBindingBuil