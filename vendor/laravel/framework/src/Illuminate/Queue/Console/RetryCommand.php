d
     */
    protected function runCallable()
    {
        $callable = $this->action['uses'];

        return $callable(...array_values($this->resolveMethodDependencies(
            $this->parametersWithoutNulls(), new ReflectionFunction($this->action['uses'])
        )));
    }

    /**
     * Run the route action and return the response.
     *
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function runController()
    {
        return $this->controllerDispatcher()->dispatch(
            $this, $this->getController(), $this->getControllerMethod()
        );
    }

    /**
     * Get the controller instance for the route.
     *
     * @return mixed
     */
    public function getController()
    {
        if (! $this->controller) {
            $class = $this->parseControllerCallback()[0];

            $this->controller = $this->container->make(ltrim($class, '\\'));
        }

        return $this->controller;
    }

    /**
     * Get the controller method used for the route.
     *
     * @return string
     */
    protected function getControllerMethod()
    {
        return $this->parseControllerCallback()[1];
    }

    /**
     * Parse the controller.
     *
     * @return array
     */
    protected function parseControllerCallback()
    {
        return Str::parseCallback($this->action['uses']);
    }

    /**
     * Determine if the route matches given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $includingMethod
     * @return bool
     */
    public function matches(Request $request, $includingMethod = true)
    {
        $this->compileRoute();

        foreach ($this->getValidators() as $validator) {
            if (! $includingMethod && $validator instanceof MethodValidator) {
                continue;
            }

            if (! $validator->matches($this, $request)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Compile the route into a Symfony CompiledRoute instance.
     *
    