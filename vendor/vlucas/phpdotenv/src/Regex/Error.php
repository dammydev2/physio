  $processors = self::processors();
        }
        if (is_array($processors) === false && is_callable($processors)) {
            $processors = [$processors];
        }
        foreach ($processors as $processor) {
            $processor($this);
        }
    }

    /**
     * Get direct access to the processors array.
     * @return array reference
     */
    public static function &processors()
    {
        if (!self::$processors) {
            // Add default processors.
            self::$processors = [
                new MergeIntoSwagger(),
                new BuildPaths(),
                new HandleReferences(),
                new AugmentDefinitions(),
                new AugmentProperties(),
                new InheritProperties(),
                new AugmentOperations(),
                new AugmentParameters(),
                new CleanUnmerged(),
            ];
        }
        return self::$processors;
    }

    /**
     * Register a processor
     * @param Closure $processor
     */
    public static function registerProcessor($processor)
    {
        array_push(self::processors(), $processor);
    }

    /**
     * Unregister a processor
     * @param Closure $processor
     */
    public static function unregisterProcessor($processor)
    {
        $processors = &self::processors();
        $key = array_search($processor, $processors, tr