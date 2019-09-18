<?php

namespace Illuminate\Routing;

use Illuminate\Support\Str;

class ResourceRegistrar
{
    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];

    /**
     * The parameters set for this resource instance.
     *
     * @var array|string
     */
    protected $parameters;

    /**
     * The global parameter mapping.
     *
     * @var array
     */
    protected static $parameterMap = [];

    /**
     * Singular global parameters.
     *
     * @var bool
     */
    protected static $singularParameters = true;

    /**
     * The verbs used in the resource URIs.
     *
     * @var array
     */
    protected static $verbs = [
        'create' => 'create',
        'edit' => 'edit',
    ];

    /**
     * Create a new resource registrar instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return vo