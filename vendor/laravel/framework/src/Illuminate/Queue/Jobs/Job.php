ATCH route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function patch($uri, $action = null)
    {
        return $this->addRoute('PATCH', $uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function delete($uri, $action = null)
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Register a new OPTIONS route with the router.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function options($uri, $action = null)
    {
        return $this->addRoute('OPTIONS', $uri, $action);
    }

    /**
     * Register a new route responding to all verbs.
     *
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function any($uri, $action = null)
    {
        return $this->addRoute(self::$verbs, $uri, $action);
    }

    /**
     * Register a new Fallback route with the router.
     *
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function fallback($action)
    {
        $placeholder = 'fallbackPlaceholder';

        return $this->addRoute(
            'GET', "{{$placeholder}}", $action
        )->where($placeholder, '.*')->fallback();
    }

    /**
     * Create a redirect from one URI to another.
     *
     * @param  string  $uri
     * @param  string  $destination
     * @param  int  $status
     * @return \Illuminate\Routing\Route
     */
    public function redirect($uri, $destination, $status = 302)
    {
        return $this->any($uri, '\Illuminate\Routing\RedirectController')
                ->defaults('destination', $destination)
                ->defaults('status', $status);
    }

    /**
     * Create a permanent redirect from one URI to another.
     *
     * @param  string  $uri
     * @param  string  $destination
     * @return \Illuminate\Routing\Route
     */
    public function permanentRedirect($uri, $destination)
    {
        return $this->redirect($uri, $destination, 301);
    }

    /**
     * Register a new route that returns a view.
     *
     * @param  string  $uri
     * @param  string  $view
     * @param  array  $data
     * @return \Illuminate\Routing\Route
     */
    public function view($uri, $view, $data = [])
    {
        return $this->match(['GET', 'HEAD'], $uri, '\Illuminate\Routing\ViewController')
                ->defaults('view', $view)
                ->defaults('data', $data);
    }

    /**
     * Register a new route with the given verbs.
     *
     * @param  array|string  $methods
     * @param  string  $uri
     * @param  \Closure|array|string|callable|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function match($methods, $uri, $action = null)
    {
        return $this->addRoute(array_map('strtoupper', (array) $methods), $uri, $action);
    }

    /**
     * Register an array of resource controllers.
     *
     * @param  array  $resources
     * @param  array  $options
     * @return void
     */
    public function resources(array $resources, array $options = [])
    {
        foreach ($resources as $name => $controller) {
            $this->resource($name, $controller, $options);
        }
    }

    /**
     * Route a resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\PendingResourceRegistration
     */
    public function resource($name, $controller, array $options = [])
    {
        if ($this->container && $this->container->bound(ResourceRegistrar::class)) {
            $registrar = $this->container->make(ResourceRegistrar::class);
        } else {
            $registrar = new ResourceRegistrar($this);
        }

        return new PendingResourceRegistration(
            $registrar, $name, $controller, $options
        );
    }

    /**
     * Register an array of API resource controllers.
     *
     * @param  array  $resources
     * @param  array  $options
     * @return void
     */
    public function apiResources(array $resources, array $options = [])
    {
        foreach ($resources as $name => $controller) {
            $this->apiResource($name, $controller, $options);
        }
    }

    /**
     * Route an API resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\PendingResourceRegistration
     */
    public function apiResource($name, $controller, array $options = [])
    {
        $only = ['index', 'show', 'store', 'update', 'destroy'];

        if (isset($options['except'])) {
            $only = array_diff($only, (array) $options['except']);
        }

        return $this->resource($name, $controller, array_merge([
            'only' => $only,
        ], $options));
    }

    /**
     * Create a route group with shared attributes.
     *
     * @param  array  $attributes
     * @param  \Closure|string  $routes
     * @return void
     */
    public function group(array $attributes, $routes)
    {
        $this->updateGroupStack($attributes);

        // Once we have updated the group stack, we'll load the provided routes and
        // merge in the group's attributes when the routes are created. After we
        // have created the routes, we will pop the attributes off the stack.
        $this->loadRoutes($routes);

        array_pop($this->groupStack);
    }

    /**
     * Update the group stack with the given attributes.
     *
     * @param  array  $attributes
     * @return void
     */
    protected function updateGroupStack(array $attributes)
    {
        if (! empty($this->groupStack)) {
            $attributes = $this->mergeWithLastGroup($attributes);
        }

        $this->groupStack[] = $attributes;
    }

    /**
     * Merge the given array with the last group stack.
     *
     * @param  array  $new
