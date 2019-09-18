list of bound data.
     *
     * @param  array  $bindings
     * @return $this
     */
    public function assertViewHasAll(array $bindings)
    {
        foreach ($bindings as $key => $value) {
            if (is_int($key)) {
                $this->assertViewHas($value);
            } else {
                $this->assertViewHas($key, $value);
            }
        }

        return $this;
    }

    /**
     * Get a piece of data from the original view.
     *
     * @param  string  $key
     * @return mixed
     */
    public function viewData($key)
    {
        $this->ensureResponseHasView();

        return $this->original->getData()[$key];
    }

    /**
     * Assert that the response view is missing a piece of bound data.
     *
     * @