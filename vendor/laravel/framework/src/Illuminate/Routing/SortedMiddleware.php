$callback);

        // First we will loop through the items and get the comparator from a callback
        // function which we were given. Then, we will sort the returned values and
        // and grab the corresponding values for the sorted keys from this array.
        foreach ($this->items as $key => $value) {
            $results[$key] = $callback($value, $key);
        }

        $descending ? arsort($results, $options)
            : asort($results, $options);

        // Once we have sorted all of the keys in the array, we will loop through them
        // and grab the corresponding model so we can set the underlying items list
        // to the sorted version. Then we'll just return the collection instance.
        foreach (array_keys($results) as $key) {
            $results[$key] = $this->items[$key];
        }

        return new static($results);
    }

    /**
     * Sort the collection in descending order using the given callback.
     *
     * @param  callable|string  $callback
     * @param  int  $options
     * @return static
     */
    public function sortByDesc($callback, $options = SORT_REGULAR)
    {
        return $this->sortBy($callback, $options, true);
    }

    /**
     * Sort the collection keys.
     *
     * @param  int  $options
     * @param  bool  $descending
     * @return static
     */
    public function sortKeys($options = SORT_REGULAR, $descending = false)
    {
        $items = $this->items;

        $descending ? krsort($items, $options) : ksort($items, $options);

        return new static($items);
    }

    /**
     * Sort the collection keys in descending order.
     *
     * @param  int $options
     * @return static
     */
    public function sortKeysDesc($options = SORT_REGULAR)
    {
        return $this->sortKeys($options, true);
    }

    /**
     * Splice a portion of the underlying collection array.
     *
     * @param  int  $offset
     * @param  int|null  $length
     * @param  mixed  $replacement
     * @return static
     */
    public function splice($offset, $length = null, $replacement = [])
    {
        if (func_num_args() === 1) {
            return new static(array_splice($this->items, $offset));
        }

        return new static(array_splice($this->items, $offset, $length, $replacement));
    }

    /**
     * Get the sum of the given values.
     *
     * @param  callable|string|null  $callback
     * @return mixed
     */
    public function sum($callback = null)
    {
        if (is_null($callback)) {
            return array_sum($this->items);
        }

        $callback = $this->valueRetriever($callback);

        return $this->reduce(function ($result, $item) use ($callback) {
            return $result + $callback($i