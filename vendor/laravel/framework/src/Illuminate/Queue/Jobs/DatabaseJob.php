<?php

namespace Illuminate\Routing;

use Illuminate\Support\Arr;

class RouteParameterBinder
{
    /**
     * The route instance.
     *
     * @var \Illuminate\Routing\Route
     */
    protected $route;

    /**
     * Create a new Route parameter binder instance.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @return void
     */
    public function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * Get the parameters for the route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function parameters($request)
    {
        // If the route has a regular expression for the host part of the URI, we will
        // compile that and get the parameter matches for this domain. We will then
        // merge them into this parameters array so that this array is completed.
        $parameters = $this->bindPathParameters($request);

        // If the route has a regular expression for the host part of the URI, we will
        // compile that and get the parameter matches for this domain. We will then
        // merge them into this parameters array so that this array is completed.
        if (! is_null($this->route->compiled->getHostRegex())) {
            $parameters = $this->bindHostParameters(
                $request, $parameters
            );
        }

        return $this->replaceDefaults($parameters);
    }

    /**
     * Get the parameter matches for the path portion of the URI.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function bindPathParameters($request)
    {
        $path = '/'.ltrim($request->decodedPath(), '/');

        preg_match($this->route->compiled->getRegex(), $path, $matches);

        return $this->matchToKeys(array_slice($matches, 1));
    }

    /**
     * Extract the parameter list from the host part of the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $parameters
     * @return array
     */
    protected function bindHostParameters($