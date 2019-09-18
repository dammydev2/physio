<?php

namespace Illuminate\Routing;

use Illuminate\Support\Traits\Macroable;

class PendingResourceRegistration
{
    use Macroable;

    /**
     * The resource registrar.
     *
     * @var \Illuminate\Routing\ResourceRegistrar
     */
    protected $registrar;

    /**
     * The resource name.
     *
     * @var string
     */
    protected $name;

    /**
     * The resource controller.
     *
     * @var string
     */
    protected $controller;

    /**
     * The resource options.
     *
     * @var array
     */
    protected $options = [];

    /**
     * The resource's registration status.
     *
     * @var bool
     */
    protected $registered = false;

    /**
     * Create a new pending resource registration instance.
     *
     * @param  \Illuminate\Routing\ResourceRegistrar  $registrar
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return void
     */
    public function __construct(ResourceRegistrar $registrar, $