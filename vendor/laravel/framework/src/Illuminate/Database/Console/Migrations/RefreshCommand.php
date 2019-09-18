@return mixed
     */
    protected function fireModelEvent($event, $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        // First, we will get the proper method to call on the event dispatcher, and then we
        // will attempt to fire a custom, object based event for the given event. If that
        // returns a result we can return that result, or we'll call the string events.
        $method = $halt ? 'until' : 'dispatch';

        $result = $this->filterModelEventResults(
            $this->fireCustomModelEvent($event, $method)
        );

        if ($result === false) {
            return false;
        }

        return ! empty($result) ? $result : static::$dispatcher->{$method}(
            "eloquent.{$event}: ".static::class, $this
        );
    }

    /**
     * Fire a custom model event for the given event.
     *
     * @param  string  $event
     * @param  string  $method
     * @return mixed|null
     */
    protected function fireCustomModelEvent($event, $method)
    {
        if (! isset($this->dispatchesEvents[$event])) {
            return;
        }

        $result = static::$dispatcher->$method(new $this->dispatchesEvents[$event]($this));

        if (! is_null($result)) {
            return $result;
        }
    }

    /**
     * Filter the model event results.
     *
     * @param  mixed  $result
     * @return mixed
     */
    protected function filterModelEventResults($result)
    {
        if (is_array($result)) {
            $result = array_filter($result, function ($response) {
                return ! is_null($response);
            });
        }

        return $result;
    }

    /**
     * Register a retrieved model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function retrieved($callback)
    {
        static::registerModelEvent('retrieved', $callback);
    }

    /**
     * Register a saving model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function saving($callback)
    {
        static::registerModelEvent('saving', $callback);
    }

    /**
     * Register a saved model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function saved($callback)
    {
        static::registerModelEvent('saved', $callback);
    }

    /**
     * Register an updating model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function updating($callback)
    {
        static::registerModelEvent('updating', $callback);
    }

    /**
     * Register an updated model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function updated($callback)
    {
        static::registerModelEvent('updated', $callback);
    }

    /**
     * Register a creating model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function creating($callback)
    {
        static::registerModelEvent('creating', $callback);
    }

    /**
     * Register a created model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function created($callback)
    {
        static::registerModelEvent('created', $callback);
    }

    /**
     * Register a replicating model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function replicating($callback)
    {
        static::registerModelEvent('replicating', $callback);
    }

    /**
     * Register a deleting model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function deleting($callback)
    {
        static::registerModelEvent('deleting', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function deleted($callback)
    {
        static::registerModelEvent('deleted', $callback);
    }

    /**
     * Remove all of the event listeners for the model.
     *
     * @return void
     */
    public static function flushEventListeners()
    {
        if (! isset(static::$dispatcher)) {
            return;