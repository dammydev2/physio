ng and formatting
                    // since the object we're given was already a fully instantiated object.
                    $parameters = [$passable, $stack];
                }

                $response = method_exists($pipe, $this->method)
                                ? $pipe->{$this->method}(...$parameters)
                                : $pipe(...$parameters);

                return $response instanceof Responsable
                            ? $response->toResponse($this->getContainer()->make(Request::class))
                            : $response;
            };
        };
    }

    /**
     * Parse full pipe string to get name and parameters.
     *
     * @param  string $pipe
     * @return array
     */
    protected function parsePipeString($pipe)
    {
        [$name, $parameters] = array_pad(explode(':', $pipe, 2), 2, []);

        if (is_string($parameters)) {
            $parameters = explode(',', $parameters);
        }

        return [$name, $parameters];
    }

    /**
     * Get the container instance.
     *
     * @return \Illuminate\Contracts\Container\Container
     *
     * @throws \RuntimeE