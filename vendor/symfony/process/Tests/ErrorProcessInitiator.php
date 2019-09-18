cale']);
                    break;
                }
            } while (false !== $locale = strstr($locale, '_', true));
        }

        if (null === $route = $route ?? $this->routes->get($name)) {
            throw new RouteNotFoundException(sprintf('Unable to generate a URL for the named route "%s" as such route does not exist.', $name));
        }

        // the Route has a cache of its own and is not recompiled as long as it does not get modified
        $compiledRoute = $route->compile();

        return $this->doGenerate($compiledRoute->getVariables(), $route->getDefaults(), $route->getRequirements(), $compiledRoute->getTokens(), $parameters, $name, $referenceType, $compiledRoute->getHostTokens(), $route->getSchemes());
    }

    /**
     * @throws MissingMandatoryParametersException When some parameters are missing that are mandatory for the route
     * @throws InvalidParameterException         