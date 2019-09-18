 $this->action, true);
    }

    /**
     * Determine if the route only responds to HTTPS requests.
     *
     * @return bool
     */
    public function httpsOnly()
    {
        return $this->secure();
    }

    /**
     * Determine if the route only responds to HTTPS requests.
     *
     * @return bool
     */
    public function secure()
    {
        return in_array('https', $this->action, true);
    }

    /**
     * Get or set the domain for the route.
     *
     * @param  string|null  $domain
     * @return $this|string|null
     */
    public function domain($domain = null)
    {
        if (is_null($domain)) {
            return $this->getDomain();
        }

        $this->action['domain'] = $domain;

        return $this;
    }

    /**
     * Get the domain defined for the route.
     *
     * @return string|null
     */
    public function getDomain()
    {
        return isset($this->action['domain'])
                ? str_replace(['http://', 'https://'], '', $this->action['domain']) : null;
    }

    /**
     * Get the prefix of the route instance.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->action['prefix'] ?? null;
    }

    /**
     * Add a prefix to the route URI.
     *
     * @param  string  $prefix
     * @return $this
     */
    public function prefix($prefix)
    {
        $uri = rtrim($prefix, '/').'/'.ltrim($this->uri, '/');

        $this->uri = trim($uri, '/');

        return $this;
    }

    /**
     * Get the URI associated with the route.
     *
     * @return string
     */
    public function uri()
    {
        return $this->uri;
    }

    /**
     * Set the URI that the route responds to.
     *
     * @param  string  $uri
     * @return $this
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get the name of the route instance.
     *
     * @return string
     */
    public function getName()
    {
        return $this->action['as'] ?? null;
    }

    /**
     * Add or change the route name.
     *
     * @param  string  $name
     * @return $this
     */
    public function name($name)
    {
        $this->action['as'] = isset($this->action['as']) ? $this->action['as'].$name : $name;

        return $this;
    }

    /**
     * Determine whether the route's name matches the given patterns.
     *
     * @param  mixed  ...$patterns
     * @return bool
     */
    public function named(...$patterns)
    {
        if (is_null($routeName = $this->getName())) {
            return false;
        }

        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set the handler for the route.
     *
     * @param  \Closure|string  $action
     * @return $this
     */
    public function uses($action)
    {
        $action = is_string($action) ? $this->addGroupNamespaceToStringUses($action) : $action;

        return $this->setAction(array_merge($this->action, $this->parseAction([
            'uses' => $action,
            'controller' => $action,
        ])));
    }

    /**
     * Parse a string based action for the "uses" fluent method.
     *
     * @param  string  $action
     * @return string
     */
    protected function addGroupNamespaceToStringUses($action)
    {
        $groupStack = last($this->router->getGroupStack());

        if (isset($groupStack['namespace']) && strpos($action, '\\') !== 0) {
            return $groupStack['namespace'].'\\'.$action;
        }

        return $action;
    }

    /**
     * Get the action name for the route.
     *
     * @return string
     */
    public function getActionName()
    {
        return $this->action['controller'] ?? 'Closure';
    }

    /**
     * Get the method name of the route action.
     *
     * @return string
     */
    public function getActionMethod()
    {
        return Arr::last(explode('@', $this->getActionName()));
    }

    /**
     * Get the action array or one of its properties for the route.
     *
     * @param  string|null  $key
     * @return mixed
     */
    public function getAction($key = null)
    {
        return Arr::get($this->action, $key);
    }

    /**
     * Set the action array for the route.
     *
     * @param  array  $action
     * @return $this
     */
    public function setAction(array $action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get all middleware, including the ones from the controller.
     *
     * @return array
     */
    public function gatherMiddleware()
    {
        if (! is_null($this->computedMiddleware)) {
            return $this->computedMiddleware;
        }

        $this->computedMiddleware = [];

        return $this->computedMiddleware = array_unique(array_merge(
            $this->middleware(), $this->controllerMiddleware()
        ), SORT_REGULAR);
    }

    /**
     * Get or set the middlewares attached to the route.
     *
     * @param  array|string|null $middleware
     * @return $this|array
     */
    public function middleware($middleware = null)
    {
        if (is_null($middleware)) {
            return (array) ($this->action['middleware'] ?? []);
        }

        if (is_string($middleware)) {
            $middleware = func_get_args();
        }

        $this->action['middleware'] = array_merge(
            (array) ($this->action['middleware'] ?? []), $middleware
        );

        return $this;
    }

    /**
     * Get the middleware for the route's controller.
     *
     * @return array
     */
    public function controllerMiddleware()
    {
        if (! $this->isControllerAction()) {
            return [];
        }

        return $this->controllerDispatcher()->getMiddleware(
            $this->getController(), $this->getControllerMethod()
        );
    }

    /**
     * Get the dispatcher for the route's controller.
     *
     * @return \Illuminate\Routing\Contracts\ControllerDispatcher
     */
    public function controllerDispatcher()
    {
        if ($this->container->bound(ControllerDispatcherContract::class)) {
            return $this->container->make(ControllerDispatcherContract::class);
        }

        return new ControllerDispatcher($this->container);
    }

    /**
     * Get the route validators for the instance.
     *
     * @return array
     */
    public static function getValidators()
    {
        if (isset(static::$validators)) {
            return static::$validators;
        }

        