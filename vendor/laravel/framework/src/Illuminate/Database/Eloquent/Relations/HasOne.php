f (is_array($value)) {
            $this->bindings[$type] = array_values(array_merge($this->bindings[$type], $value));
        } else {
            $this->bindings[$type][] = $value;
        }

        return $this;
    }

    /**
     * Merge an array of bindings into our bindings.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return $this
     */
    public function mergeBindings(self $query)
    {
        $this->bindings = array_merge_recursive($this->bindings, $query->bindings);

        return $this;
    }

    /**
     * Remove all of the expressions from a list of bindings.
     *
     * @param  array  $bindings
     * @return array
     */
    protected function cleanBindings(array $bindings)
    {
        return array_values(array_filter($bindings, function ($binding) {
            return ! $binding instanceof Expression;
        }));
    }

    /**
     * Get the database connection instance.
     *
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Get the database query processor instance.
     *
     * @return \Illuminate\Database\Query\Processors\Processor
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    /**
     * Get the query grammar instance.
     *
     * @return \Illuminate\Database\Query\Grammars\Grammar
     */
    public function getGrammar()
    {
        return $this->grammar;
    }

    /**
     * Use the write pdo for query.
     *
     * @return $this
     */
    public function useWritePdo()
    {
        $this->useWritePdo = true;

        return $this;
    }

    /**
     * Clone the query without the given properties.
     *
  