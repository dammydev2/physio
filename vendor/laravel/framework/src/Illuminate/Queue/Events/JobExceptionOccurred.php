<?php

namespace Illuminate\Routing;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RouteBinding
{
    /**
     * Create a Route model binding for a given callback.
     *
     * @param  \Illuminate\Container\Container  $container
     * @param  \Closure|string  $binder
     * @return \Closure
     */
    public static function forCallback($container, $binder)
    {
        if (is_string($binder)) {
            return static::createClassBinding($container, $binder);
        }

        return $binder;
    }

    /**
     * Create a class based binding using the IoC container.
     *
     * @param  \Illuminate\Container\Container  $container
     * @param  string  $binding
     * @return \Closure
     */
    protected st