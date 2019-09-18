    protected function whereNotIn(Builder $query, $where)
    {
        if (! empty($where['values'])) {
            return $this->wrap($where['column']).' not in ('.$this->parameterize($where['values']).')';
        }

        return '1 = 1';
    }

    /**
     * Compile a "where not in raw" clause.
     *
     * For safety, whereIntegerInRaw ensures this method is only used with integer values.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereNotInRaw(Builder $query, $where)
    {
        if (! empty($where['values'])) {
            return $this->wrap($where['column']).' not in ('.implode(', ', $where['values']).')';
        }

        return '1 = 1';
    }

    /**
     * Compile a where in sub-select clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereInSub(Builder $query, $where)
    {
        return $this->wrap($where['column']).' in ('.$this->compileSelect($where['query']).')';
    }

    /**
     * Compile a where not in sub-select clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereNotInSub(Builder $query, $where)
    {
        return $this->wrap($where['column']).' not in ('.$this->compileSelect($where['query']).')';
    }

    /**
     * Compile a "where in raw" clause.
     *
     * For safety, whereIntegerInRaw ensures this method is only used with integer values.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     * @return string
     */
    protected function whereInRaw(Builder $query, $where)
    {
        if (! empty($where['values'])) {
            return $this->wrap($where['column']).' in ('.implode(', ', $where['values']).')';
        }

        return '0 = 1';
    }

    /**
     * Compile a "where null" clause.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $where
     *