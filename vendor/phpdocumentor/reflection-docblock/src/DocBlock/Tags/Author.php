       } else {
            return Option::fromValue($value, $noneValue);
        }
    }

    /**
     * Returns the value if available, or throws an exception otherwise.
     *
     * @throws \RuntimeException if value is not available
     *
     * @return mixed
     */
    abstract public function get();

    /**
     * Returns the value if available, or the default value if not.
     *
     * @param mixed $default
     *
     * @return mixed
     */
    abstract public function getOrElse($default);

    /**
     * Returns the value if available, or the results of the callable.
     *
     * This is preferable over ``getOrElse`` if the computation of the default
     * value is expensive.
     *
     * @param callable $callable
     *
     * @return mixed
     */
    abstract public function getOrCall($callable);

    /**
     * Returns the value if available, or throws the passed exception.
     *
     * @param \Exception $ex
     *
     * @return mixed
     */
    abstract public function getOrThrow(\Exception $ex);

    /**
     * Returns true if no value is available, false otherwise.
     *
     * @return boolean
     */
    abstract public function isEmpty();

    /**
     * Returns true if a value is available, false otherwise.
     *
     * @return boolean
     */
    abstract public function isDefined();

    /**
     * Returns this option if non-empty, or the passed option otherwise.
     *
     * This can be used to try multiple alternatives, and is especially useful
     * with lazy evaluating options:
     *
     * ```php
     *     $repo->findSomething()
     *         ->orElse(new LazyOption(array($repo, 'findSomethingElse')))
     *         ->orElse(new LazyOption(array($repo, 'createSomething')));
     * ```
     *
     * @param Option $else
     *
     * @return Option
     */
    abstract public function orElse(Option $else);

    /**
     * This is similar to map() below except that the return value has no meaning;
     * the passed callable is simply executed if the option is non-empty, and
     * ignored if the option is empty.
     *
     * In all cases, the return value of the callable is discarded.
     *
     * ```php
     *     $comment->getMaybeFile()->ifDefined(function($file) {
     *         // Do something with $file here.
     *     });
     * ```
     *
     * If you're looking for something like ``ifEmpty``, you can use ``getOrCall``
     * and ``getOrElse`` in these cases.
     *
     * @deprecated Use forAll() instead.
     *
     * @param 