<?php

namespace Illuminate\Routing;

use Closure;
use LogicException;
use ReflectionFunction;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Container\Container;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Routing\Matching\UriValidator;
use Illuminate\Routing\Matching\HostValidator;
use Illuminate\Routing\Matching\MethodValidator;
use Illuminate\Routing\Matching\SchemeValidator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Contracts\ControllerDispatcher as ControllerDispatcherContract;

class Route
{
    use Macroable, RouteDependencyResolverTrait;

    /**
     * The URI pattern the route responds to.
     *
     * @var string
     */
 