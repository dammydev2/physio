tic
     */
    public function whereNotInStrict($key, $values)
    {
        return $this->whereNotIn($key, $values, true);
    }

    /**
     * Filter the items, removing any items that don't match the given type.
     *
     * @param  string  $type
     * @return static
     */
    public function whereInstanceOf($type)
    {
        return $this->filter(function ($value) use ($type) {
            return $value instanceof $type;
        });
    }

    /**
     * Get the first item from the collection.
     *
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        return Arr::first($this->items, $callback, $default);
    }

    /**
     * Get the first item by the given key value pair.
     *
     * @param  string  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return mixed
     */
    public function firstWhere($key, $operator = null, $value = null)
    {
        return $this->first($this->operatorForWhere(...func_get_args()));
    }

    /**
     * Get a flattened array of the items in the collection.
     *
     * @param  int  $depth
     * @return static
     */
    public function flatten($depth = INF)
    {
        return new static(Arr::flatten($this->items, $depth));
    }

    /**
     * Flip the items in the collection.
     *
     * @return static
     */
    public function flip()
    {
        return new static(array_flip($this->items));
    }

    /**
     * Remove an item from the collection by key.
     *
     * @param  string|array  $keys
     * @return $this
     */
    public function forget($keys)
    {
        foreach ((array) $keys as $key) {
            $this->offsetUnset($key);
        }

        return $this;
    }

    /**
     * Get an item from the collection by key.
     *
     * @param  mixed  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->offsetExists($key)) {
            return $this->items[$key];
        }

        return value($default);
    }

    /**
     * Group an associative array by a field or using a callback.
     *
     * @param  array|callable|string  $groupBy
     * @param  bool  $preserveKeys
     * @return static
     */
    public function groupBy($groupBy, $preserveKeys = false)
    {
        if (is_array($groupBy)) {
            $nextGroups = $groupBy;

            $groupBy = array_shift($nextGroups);
        }

        $groupBy = $this->valueRetriever($groupBy);

        $results = [];

        foreach ($this->items as $key => $value) {
            $groupKeys = $groupBy($value, $key);

            if (! is_array($groupKeys)) {
                $groupKeys = [$groupKeys];
            }

            foreach ($groupKeys as $groupKey) {
                $groupKey = is_bool($groupKey) ? (int) $groupKey : $groupKey;

                if (! array_key_exists($groupKey, $results)) {
                    $results[$groupKey] = new static;
                }

                $results[$groupKey]->offsetSet($preserveKeys ? $key : null, $value);
            }
        }

        $result = new static($results);

        if (! empty($nextGroups)) {
            return $result->map->groupBy($nextGroups, $preserveKeys);
        }

        return $result;
    }

    /**
     * Key an associative array by a field or using a callback.
     *
     * @param  callable|string  $keyBy
     * @return static
     */
    public function keyBy($keyBy)
    {
        $keyBy = $this->valueRetriever($keyBy);

        $results = [];

        foreach ($this->items as $key => $item) {
            $resolvedKey = $keyBy($item, $key);

            if (is_object($resolvedKey)) {
                $resolvedKey = (string) $resolvedKey;
            }

            $results[$resolvedKey] = $item;
        }

        return new static($results);
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function has($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $value) {
            if (! $this->offsetExists($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Concatenate values of a given key as a string.
     *
     * @param  string  $value
     * @param  string  $glue
     * @return string
     */
    public function implode($value, $glue = null)
    {
        $first = $this->first();

        if (is_array($first) || is_object($first)) {
            return implode($glue, $this->pluck($value)->all());
        }

        return implode($value, $this->items);
    }

    /**
     * Intersect the collection with the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function intersect($items)
    {
        return new static(array_intersect($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Intersect the collection with the given items by key.
     *
     * @param  mixed  $items
     * @return static
     */
    public function intersectByKeys($items)
    {
        return new static(array_intersect_key(
            $this->items, $this->getArrayableItems($items)
        ));
    }

    /**
     * Determine if the collection is empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Determine if the collection is not empty.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return ! $this->isEmpty();
    }

    /**
     * Determine if the given value is callable, but not a string.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function useAsCallable($value)
    {
        return ! is_string($value) && is_callable($value);
    }

    /**
     * Join all items from the collection using a string. The final items can use a separate glue string.
     *
     * @param  string  $glue
     * @param  string  $finalGlue
     * @return string
     */
    public function join($glue, $finalGlue = '')
    {
        if ($finalGlue === '') {
            return $this->implode($glue);
        }

        $count = $this->count();

        if ($count === 0) {
            return '';
        }

        if ($count === 1) {
            return $this->last();
        }

        $collection = new static($this->items);

        $finalItem = $collection->pop();

        return $collection->implode($glue).$finalGlue.$finalItem;
    }

    /**
     * Get the keys of the collection items.
     *
     * @return static
     */
    public function keys()
    {
        return new static(array_keys($this->items));
    }

    /**
     * Get the last item from the collection.
     *
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public function last(callable $callback = null, $default = null)
    {
        return Arr::last($this->items, $callback, $default);
    }

    /**
     * Get the values of a given key.
     *
     * @param  string|array  $value
     * @param  string|null  $key
     * @return static
     */
    public function pluck($value, $key = null)
    {
        return new static(Arr::pluck($this->items, $value, $key));
    }

    /**
     * Run a map over each of the items.
     *
     * @param  callable  $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->items);

        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }

    /**
     * Run a map over each nested chunk of items.
     *
     * @param  callable  $callback
     * @return static
     */
    public function mapSpread(callable $callback)
    {
        return $this->map(function ($chunk, $key) use ($callback) {
            $chunk[] = $key;

            return $callback(...$chunk);
        });
    }

    /**
     * Run a dictionary map over the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @param  callable  $callback
     * @return static
     */
    public function mapToDictionary(callable $callback)
    {
        $dictionary = [];

        foreach ($this->items as $key => $item) {
            $pair = $callback($item, $key);

            $key = key($pair);

            $value = reset($pair);

            if (! isset($dictionary[$key])) {
                $dictionary[$key] = [];
            }

            $dictionary[$key][] = $value;
        }

        return new static($dictionary);
    }

    /**
     * Run a grouping map over the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @param  callable  $callb