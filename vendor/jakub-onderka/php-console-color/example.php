       return $this;
    }

    /**
     * Create the ability callback for a callback string.
     *
     * @param  string  $ability
     * @param  string  $callback
     * @return \Closure
     */
    protected function buildAbilityCallback($ability, $callback)
    {
        return function () use ($ability, $callback) {
            if (Str::contains($callback, '@')) {
                [$class, $method] = Str::parseCallback($callback);
            } else {
                $class = $callback;
            }

            $policy = $this->resolvePolicy($class);

            $arguments = func_get_args();

            $user = array_shift($arguments);

            $result = $this->callPolicyBefore(
                $policy, $user, $ability, $arguments
            );

            if (! is_null($result)) {
                return $result;
            }

            return isset($method)
                    ? $policy->{$method}(...func_get_args())
                    : $policy(...func_get_args());
     