$prev = true;
                }

                $tree = new StaticPrefixCollection();
                foreach ($routes->all() as $name => $route) {
                    preg_match('#^.\^(.*)\$.[a-zA-Z]*$#', $route->compile()->getRegex(), $rx);

                    $state->vars = [];
                    $regex = preg_replace_callback('#\?P<([^>]++)>#', $state->getVars, $rx[1]);
                    if ($hasTrailingSlash = '/' !== $regex && '/' === $regex[-1]) {
                        $regex = substr($regex, 0, -1);
                    }
                    $hasTrailingVar = (bool) preg_match('#\{\w+\}/?$#', $route->getPath());

                    $tree->addRoute($regex, [$name, $regex, $state->vars, $route, $hasTrailingSlash, $hasTrailingVar]);
                }

                $code .= $this->compileStaticPrefixCollection($tree, $state, 0, $conditions);
            }
            if ($matchHost) {
                $code .= "\n        .')'";
                $state->regex .= ')';
            }
            $rx = ")/?$}{$modifiers}";
            $code .= "\n        .'{$rx}',";
            $state->regex .= $rx;
            $state->markTail = 0;

            // if the regex is too large, throw a signaling exception to recompute with smaller chunk size
            set_error_handler(function ($type, $message) { throw 0 === strpos($message, $this->signalingException->getMessage()) ? $this->signalingException : new \ErrorException($message); });
            try {
                preg_match($state->regex, '');
            } finally {
                restore_error_handler();
            }

            $regexpList[$startingMark] = $state->regex;
        }

        $state->routes[$state->mark][] = [null, null, null, null, false, false, 0];
        unset($state->getVars);

        return [$regexpList, $state->routes, $code];
    }

    /**
     * Compiles a regexp tree of subpatterns that matches nested same-prefix routes.
     *
     * @param \stdClass $state A simple state object that keeps track of the progress of the compilation,
     *                         and gathers the generated switch's "case" and "default" statements
     */
    private function compileStaticPrefixCollection(StaticPrefixCollection $tree, \stdClass $state, int $prefixLen, array &$conditions): string
    {
        $code = '';
        $prevRegex = null;
        $routes = $tree->getRoutes();

        foreach ($routes as $i => $route) {
            if ($route instanceof StaticPrefixCollection) {
                $prevRegex = null;
                $prefix = substr($route->getPrefix(), $prefixLen);
                $state->mark += \strlen($rx = "|{$prefix}(?");
                $code .= "\n            .".self::export($rx);
                $state->regex .= $rx;
                $code .= $this->indent($this->compileStaticPrefixCollection($route, $state, $prefixLen + \strlen($prefix), $conditions));
                $code .= "\n            .')'";
                $state->regex .= ')';
                ++$state->markTail;
                continue;
            }

            list($name, $regex, $vars, $route, $hasTrailingSlash, $hasTrailingVar) = $route;
            $compiledRoute = $route->compile();
            $vars = array_merge($state->hostVars, $vars);

            if ($compiledRoute->getRegex() === $prevRegex) {
                $state->routes[$state->mark][] = $this->compileRoute($route, $name, $vars, $hasTrailingSlash, $hasTrailingVar, $conditions);
                continue;
            }

            $state->mark += 