Builder $query, $where)
    {
        $not = $where['not'] ? 'not ' : '';

        return $not.$this->compileJsonContains(
            $where['column'], $this->parameter($where['value'])
        );
    }

    /**
     * Compile a "JSON contains" statement into SQL.
     *
     * @param  string  $column
     * @param  string  $value
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function compileJsonContains($column, $value)
    {
        throw new RuntimeException('This database engine does not support JSON contains operations.');
    }

    /**
     * Prepare the binding for a "JSON contains" statement.
     *
     * @param  mixed  $binding
     * @return string
     */
    public function prepareBindingForJsonContains($binding)
    {
        return json_encode($binding);
    }

    /**
     * Compile a "where JSON length" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereJsonLength(Builder $query, $where)
    {
        return $this->compileJsonLength(
            $where['column'], $where['operator'], $this->parameter($where['value'])
        );
    }

    /**
     * Compile a "JSON length" statement into SQL.
     *
     * @param  string  $column
     * @param  string  $operator
     * @param  string  $value
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function compileJsonLength($column, $operator, $value)
    {
        throw new RuntimeException('This database engine does not support JSON length operations.');
    }

    /**
     * Compile the "group by" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $groups
     * @return string
     */
    protected function compileGro