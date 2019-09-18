<?php

namespace Illuminate\Routing;

use Countable;
use ArrayIterator;
use IteratorAggregate;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class RouteCollection implements Countable, IteratorAggregate
{
    /**
     * An array of the routes keyed by method.
     *
     * @var array
     */
    protected $routes = [];

    /**
     * A flattened array of all of the routes.
     *
     * @var array
     */
    protected $allRoutes = [];

    /**
     * A look-up table of routes by their names.
     *
     * @var array
     */
    protected $nameList = [];

    /**
     * A look-up table of routes by contro