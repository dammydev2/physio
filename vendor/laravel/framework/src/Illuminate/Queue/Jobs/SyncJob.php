     */
    public function prependMiddlewareToGroup($group, $middleware)
    {
        if (isset($this->middlewareGroups[$group]) && ! in_array($middleware, $this->middlewareGroups[$group])) {
            array_unshift($this->middlewareGroups[$group], $middleware);
        }

        return $this;
    }

    /**
     * Add a middleware to the end of a middleware group.
     *
     * If the middleware is already in the group, it will not be added again.
     *
     * @param  string  $group
     * @param  string  $middleware
     * @return $this
     */
    public function pushMiddlewareToGroup($group, $middleware)
    {
        if (! array_key_exists($group, $this->middlewareGroups)) {
            $this->middlewareGroups[$group] = [];
        }

        if (! in_array($middleware, $this->middlewareGroups[$group])) {
            $this->middlewareGroups[$group][] = $middleware;
        }

        return $this;
    }

    /**
     * Add a new route parameter binder.
     *
     * @param  string  $key
     * @param  string|callable  $binder
     * @return void
     */
    public function bind($key, $binder)
    {
        $this->binders[str_replace('-', '_', $key)] = RouteBinding::forCallback(
            $this->container, $binder
        );
    }

    /**
     * Register a model binder for a wildcard.
     *
     * @param  string  $key
     * @param  string  $class
     * @param  \Closure|null  $callback
     * @return void
     */
    public function model($key, $class, Closure $callback = null)
    {
        $this->bind($key, RouteBinding::forModel($this->container, $class, $callback));
    }

    /**
     * Get the binding callback for a given binding.
     *
     * @param  s