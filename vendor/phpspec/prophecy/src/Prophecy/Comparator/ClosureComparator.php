                'a `MethodProphecy::withArguments()` argument, but got %s.',
                gettype($arguments)
            ));
        }

        $this->argumentsWildcard = $arguments;

        return $this;
    }

    /**
     * Sets custom promise to the prophecy.
     *
     * @param callable|Promise\PromiseInterface $promise
     *
     * @return $this
     *
     * @throws \Prophecy\Exception\InvalidArgumentException
     */
    public function will($promise)
    {
        if (is_callable($promise)) {
            $promise = new Promise\CallbackPromise($promise);
        }

        if (!$promise instanceof Promise\PromiseInterface) {
            throw new InvalidArgumentException(sprintf(
                'Expected callable or instance of PromiseInterface, but got %s.',
                gettype($promise)
            ));
        }

        $this->bindToObjectProphecy();
        $this->promise = $promise;

        return $this;
    }

    /**
     * Sets return promise to the prophecy.
     *
     * @see \Prophecy\Promise\ReturnPromise
     *
     * @return $this
     */
    publi