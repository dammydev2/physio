);
        if ($reflector->getConstructor()) {
            return $reflector->newInstanceArgs(
                $this->createDependenciesFor($itemName)
                );
        }

        return $reflector->newInstance();
    }

    /** Create and register a shared instance of $itemName */
    private function createSharedInstance($itemName)
    {
        if (!isset($this->store[$itemName]['instance'])) {
            $this->store[$itemName]['instance'] = $this->createNewInstance($itemName);
        }

        return $this->store[$itemName]['instance'];
    }

    /** Get the current endpoint in the store */
    private function &getEndPoint()
    {
        if (!isset($this->endPoint)) {
            throw new BadMethodCallException(
                'Component must first be registered by calling register()'
                );
        }

        return $this->endPoint;
    }

    /** Get an argument list with dependencies resolved */
    private function r