ble.
     *
     * If the option is non-empty, the callable is applied, and if it returns true,
     * the option itself is returned; otherwise, None is returned.
     *
     * @param callable $callable
     *
     * @return Option
     */
    abstract public function filter($callable);

    /**
     * If the option is empty, it is returned immediately without applying the callable.
     *
     * If the option is non-empty, the callable is applied, and if it returns false,
     * the option itself is returned; otherwise, None is returned.
     *
     * @param callable $callable
     *
     * @return Option
     */
    abstract public function filterNot($callable);

    /**
     * If the option is empty, it is returned immediately.
     *
     * If the option is non-empty, and its value does not equal the passed value
     * (via a shallow comparison ===), then None is returned. Otherwise, the
     * Option is returned.
     *
     * In other words, this will filter all but the passed value.
     *
     * @param mixed $value
     *
     * @return Option
     */
    abstract public function select($value);

    /**
     * If the option is empty, it is returned immediately.
     *
     * If the opti