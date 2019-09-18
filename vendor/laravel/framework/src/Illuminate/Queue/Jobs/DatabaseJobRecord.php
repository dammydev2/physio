<?php

namespace Illuminate\Routing;

use Closure;
use ArrayObject;
use JsonSerializable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\Routing\BindingRegistrar;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Illuminate\Contracts\Routing\Registrar as RegistrarContract;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * @mixin \Illuminate\Routing\RouteRegistrar
 */
class Router implements RegistrarContract, BindingRegistrar
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
