*/
    protected function compileOrders(Builder $query, $orders)
    {
        if (! empty($orders)) {
            return 'order by '.implode(', ', $this->compileOrdersToArray($query, $orders));
        }

        return '';
    }

    /**
     * Compile the query orders to an array.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  array  $orders
     * @return array
     */
    protected function compileOrdersToArray(Builder $query, $orders)
    {
        return array_map(function ($order) {
            return $order['sql'] ?? $this->wrap($order['column']).' '.$order['direction'];
        }, $orders);
    }

    /**
     * Compile the random statement into SQL.
     *
     * @param  string  $seed
     * @return string
     */
    public function compileRandom($seed)
    {
        return 'RANDOM()';
    }

    /**
     * Compile the "limit" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $limit
     * @return string
     */
    protected function compileLimit(Builder $query, $limit)
    {
        return 'limit '.(int) $limit;
    }

    /**
     * Compile the "offset" portions of the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  int  $offset
     * @return string
     */
    protected function compileOffset(Builder $query, $offset)
    {
        return 'offset '.(int) $offset;
    }

    /**
     * Compile the "union" queries attached to the main query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    protected function compileUnions(Builder $query)
    {
        $sql = '';

        foreach ($query->unions as $union) {
            $sql .= $this->compileUnion($union);
        }

        if (! empty($query->unionOrders)) {
            $sql .= ' '.$this->compileOrders($query, $query->unionOrders);
        }

        if (isset($query->unionLimit)) {
            $sql .= ' '.$this->compileLimit($query, $query->unionLimit);
        }

        if (isset($query->unionOffset)) {
            $sql .= ' '.$this->compileOffset($query, $query->unionOffset);
        }

        return ltrim($sql);
    }

    /**
     * Compile a single union statement.
     *
     * @param  array  $union
     * @return string
     */
    protected function compileUnion(array $union)
    {
        $conjunction = $union['all'] ? ' union all ' : ' union ';

        return $conjunction.$union['query']->toSql();
    }

    /**
     * Compile a union aggregate query into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    protected function compileUnionAggregate(Builder $query)
    {
        $sql = $this->compileAggregate($query, $query->aggregate);

        $query->aggregate = null;

        return $sql.' from ('.$this->compileSelect($query).') as '.$this->wrapTable('temp_table');
    }

    /**
     * Compile an exists statement into SQL.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
 