imple next and previous links.
     *
     * This is more efficient on larger data-sets, etc.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function simplePaginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $this->skip(($page - 1) * $perPage)->take($perPage + 1);

        return $this->simplePaginator($this->get($columns), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    /**
     * Get the count of the total records for the paginator.
     *
     * @param  array  $columns
     * @return int
     */
    public function getCountForPagination($columns = ['*'])
    {
        $results = $this->runPaginationCountQuery($columns);

        // Once we have run the pagination count query, we will get the resulting count and
        // take into account what type of query it was. When there is a group by we will
        // just return the count of the entire results set since that will be correct.
        if (isset($this->groups)) {
            return count($results);
        } elseif (! isset($results[0])) {
            return 0;
        } elseif (is_object($results[0])) {
            return (int) $results[0]->aggregate;
        }

        return (int) array_change_key_case((array) $results[0])['aggregate'];
    }

    /**
     * Run a pagination count query.
     *
     * @param  array  $columns
     * @return array
     */
    protected function runPaginationCountQuery($columns = ['*'])
    {
        $without = $this->unions ? ['orders', 'limit', 'offset'] : ['columns', 'orders', 'limit', 'offset'];

        return $this->cloneWithout($without)
                    ->cloneWithoutBindings($this->unions ? ['order'] : ['select', 'order'])
                    ->setAggregate('count', $this->withoutSelectAliases($columns))
                    ->get()->all();
    }

    /**
     * Remove the column aliases since they will break count queries.
     *
     * @param  array  $columns
     * @return array
     */
    protected function withoutSelectAliases(array $columns)
    {
        return array_map(function ($column) {
            return is_string($column) && ($aliasPosition = stripos($column, ' as ')) !== false
                    ? substr($column, 0, $aliasPosition) : $column;
        }, $columns);
    }

    /**
     * Get a generator for the given query.
     *
     * @return \Generator
     */
    public function cursor()
    {
        if (is_null($this->columns)) {
            $this->columns = ['*'];
        }

        return $this->connection->cursor(
            $this->toSql(), $this->getBindings(), ! $this->useWritePdo
        );
    }

    /**
     * Chunk the results of a query by comparing numeric IDs.
     *
     * @param  int  $count
     * @param  callable  $callback
     * @param  string  $column
     * @param  string|null  $alias
     * @return bool
     */
    public function chunkById($count, callable $callback, $column = 'id', $alias = null)
    {
        $alias = $alias ?: $column;

        $lastId = null;

        do {
            $clone = clone $this;

            // We'll execute the query for the given page and get the results. If there are
            // no results we can just break and return from here. When there are results
            // we will call the callback with the current chunk of these results here.
            $results = $clone->forPageAfterId($count, $lastId, $column)->get();

            $countResults = $results->count();

            if ($countResults == 0) {
                break;
            }

            // On each chunk result set, we will pass them to the callback and then let the
            // developer take care of everything within the callback, which allows us to
            // keep the memory low for spinning through large result sets for working.
            if ($callback($results) === false) {
                return false;
            }

            $lastId = $results->last()->{$alias};

            unset($results);
        } while ($countResults == $count);

        return true;
    }

    /**
     * Throw an exception if the query doesn't have an orderBy clause.
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    protected function enforceOrderBy()
    {
        if (empty($this->orders) && empty($this->unionOrders)) {
            throw new RuntimeException('You must specify an orderBy clause when using this function.');
        }
    }

    /**
     * Get an array with the values of a given column.
     *
     * @param  string  $column
     * @param  string|null  $key
     * @return \Illuminate\Support\Collection
     */
    public function pluck($column, $key = null)
    {
        // First, we will need to select the results of the query accounting for the
        // given columns / key. Once we have the results, we will be able to take
        // the results and get the exact data that was requested for the query.
        $queryResult = $this->onceWithColumns(
            is_null($key) ? [$column] : [$column, $key],
            function () {
                return $this->processor->processSelect(
                    $this, $this->runSelect()
                );
            }
        );

        if (empty($queryResult)) {
            return collect();
        }

        // If the columns are qualified with a table or have an alias, we cannot use
        // those directly in the "pluck" operations since the results from the DB
        // are only keyed by the column itself. We'll strip the table out here.
        $column = $this->stripTableForPluck($column);

        $key = $this->stripTableForPluck($key);

        return is_array($queryResult[0])
                    ? $this->pluckFromArrayColumn($queryResult, $column, $key)
                    : $this->pluckFromObjectColumn($queryResult, $column, $key);
    }

    /**
     * Strip off the table name or alias from a column identifier.
     *
     * @param  string  $column
     * @return string|null
     */
    protected function stripTableForPluck($column)
    {
        return is_null($column) ? $column : last(preg_split('~\.| ~', $column));
    }

    /**
     * Retrieve column values from rows represented as objects.
     *
     * @param  array  $queryResult
     * @param  string $column
     * @param  string $key
     * @return \Illuminate\Support\Collection
     */
    protected function pluckFromObjectColumn($queryResult, $column, $key)
    {
        $results = [];

        if (is_null($key)) {
            foreach ($queryResult as $row) {
                $results[] = $row->$column;
            }
        } else {
            foreach ($queryResult as $row) {
                $results[$row->$key] = $row->$column;
            }
        }

        return collect($results);
    }

    /**
     * Retrieve column values from rows represented as arrays.
     *
     * @param  array  $queryResult
     * @param  string $column
     * @param  string $key
     * @return \Illuminate\Support\Collection
     */
    protected function pluckFromArrayColumn($queryResult, $column, $key)
    {
        $results = [];

        if (is_null($key)) {
            foreach ($queryResult as $row) {
                $results[] = $row[$column];
            }
        } else {
            foreach ($queryResult as $row) {
                $results[$row[$key]] = $row[$column];
            }
        }

        return collect($results);
    }

    /**
     * Concatenate values of a given column as a string.
     *
     * @param  string  $column
     * @param  string  $glue
     * @return string
     */
    public function implode($column, $glue = '')
    {
        return $this->pluck($column)->implode($glue);
    }

    /**
     * Determine if any rows exist for the current query.
     *
     * @return bool
     */
    public function exists()
    {
        $results = $this->connection->select(
            $this->grammar->compileExists($this), $this->getBindings(), ! $this->useWritePdo
        );

        // If the results has rows, we will get the row and see if the exists column is a
        // boolean true. If there is no results for this query we will return false as
        // there are no rows for this query at all and we can return that info here.
        if (isset($results[0])) {
            $results = (array) $results[0];

            return (bool) $results['exists'];
        }

        return false;
    }

    /**
     * Determine if no rows exist for the current query.
     *
     * @return bool
     */
    public function doesntExist()
    {
        return ! $this->exists();
    }

    /**
     * Retrieve the "count" result of the query.
     *
     * @param  string  $columns
     * @return int
     */
    public function count($columns = '*')
    {
        return (int) $this->aggregate(__FUNCTION__, Arr::wrap($columns));
    }

    /**
     * Retrieve the minimum value of a given column.
     *
     * @param  string  $column
     * @return mixed
     */
    public function min($column)
    {
        return $this->aggregate(__FUNCTION__, [$column]);
    }

    /**
     * Retrieve the maximum value of a given column.
     *
     * @param  string  $column
     * @return mixed
     */
    public function max($column)
    {
        return $this->aggregate(__FUNCTION__, [$column]);
    }

    /**
     * Retrieve the sum of the values of a given column.
     *
     * @param  string  $column
     * @return mixed
     */
    public function sum($column)
    {
        $result = $this->aggregate(__FUNCTION__, [$column]);

        return $result ?: 0;
    }

    /**
     * Retrieve the average of the values of a given column.
     *
     * @param  string  $column
     * @return mixed
     */
    public function avg($column)
    {
        return $this->aggregate(__FUNCTION__, [$column]);
    }

    /**
     * Alias for the "avg" method.
     *
     * @param  string  $column
     * @return mixed
     */
    public function average($column)
    {
        return $this->avg($column);
    }

    /**
     * Execute an aggregate function on the database.
     *
     * @param  string  $function
     * @param  array   $columns
     * @return mixed
     */
    public function aggregate($function, $columns = ['*'])
    {
        $results = $this->cloneWithout($this->unions ? [] : ['columns'])
                        ->cloneWithoutBindings($this->unions ? [] : ['select'])
                        ->setAggregate($function, $columns)
                        ->get($columns);

        if (! $results->isEmpty()) {
            return array_change_key_case((array) $results[0])['aggregate'];
        }
    }

    /**
     * Execute a numeric aggregate function on the database.
     *
     * @param  string  $function
     * @param  array   $columns
     * @return float|int
     */
    public function numericAggregate($function, $columns = ['*'])
    {
        $result = $this->aggregate($function, $columns);

        // If there is no result, we can obviously just return 0 here. Next, we will check
        // if the result is an integer or float. If it is already one of these two data
        // types we can just return the result as-is, otherwise we will convert this.
        if (! $result) {
            return 0;
        }

        if (is_int($result) || is_float($result)) {
            return $result;
        }

        // If the result doesn't contain a decimal place, we will assume it is an int then
        // cast it to one. When it does we will cast it to a float since it needs to be
        // cast to the expected data type for the developers out of pure convenience.
        return strpos((string) $result, '.') === false
                ? (int) $result : (float) $result;
    }

    /**
     * Set the aggregate property without running the query.
     *
     * @param  string  $function
     * @param  array  $columns
     * @return $this
     */
    protected function setAggregate($function, $columns)
    {
        $this->aggregate = compact('function', 'columns');

        if (empty($this->groups)) {
            $this->orders = null;

            $this->bindings['order'] = [];
        }

        return $this;
    }

    /**
     * Execute the given callback while selecting the given columns.
     *
     * After running the callback, the columns are reset to the original value.
     *
     * @param  array  $columns
     * @param  callable  $callback
     * @return mixed
     */
    protected function onceWithColumns($columns, $callback)
    {
        $original = $this->columns;

        if (is_null($original)) {
            $this->columns = $columns;
        }

        $result = $callback();

        $this->columns = $original;

        return $result;
    }

    /**
     * Insert a new record into the database.
     *
     * @param  array  $values
     * @return bool
     */
    public function insert(array $values)
    {
        // Since every insert gets treated like a batch insert, we will make sure the
        // bindings are structured in a way that is convenient when building these
        // inserts statements by verifying these elements are actually an array.
        if (empty($values)) {
            return true;
        }

        if (! is_array(reset($values))) {
            $values = [$values];
        }

        // Here, we will sort the insert keys for every record so that each insert is
        // in the same order for the record. We need to make sure this is the case
        // so there are not any errors or problems when inserting these records.
        else {
            foreach ($values as $key => $value) {
                ksort($value);

                $values[$key] = $value;
            }
        }

        // Finally, we will run this query against the database connection and return
        // the results. We will need to also flatten these bindings before running
        // the query so they are all in one huge, flattened array for execution.
        return $this->connection->insert(
            $this->grammar->compileInsert($this, $values),
            $this->cleanBindings(Arr::flatten($values, 1))
        );
    }

    /**
     * Insert a new record and get the value of the primary key.
     *
     * @param  array  $values
     * @param  string|null  $sequence
     * @return int
     */
    public function insertGetId(array $values, $sequence = null)
    {
        $sql = $this->grammar->compileInsertGetId($this, $values, $sequence);

        $values = $this->cleanBindings($values);

        return $this->processor->processInsertGetId($this, $sql, $values, $sequence);
    }

    /**
     * Insert new records into the table using a subquery.
     *
     * @param  array  $columns
     * @param  \Closure|\Illuminate\Database\Query\Builder|string  $query
     * @return bool
     */
    public function insertUsing(array $columns, $query)
    {
        [$sql, $bindings] = $this->createSub($query);

        return $this->connection->insert(
            $this->grammar->compileInsertUsing($this, $columns, $sql),
            $this->cleanBindings($bindings)
        );
    }

    /**
     * Update a record in the database.
     *
     * @param  array  $values
     * @return int
     */
    public function update(array $values)
    {
        $sql = $this->grammar->compileUpdate($this, $values);

        return $this->connection->update($sql, $this->cleanBindings(
            $this->grammar->prepareBindingsForUpdate($this->bindings, $values)
        ));
    }

    /**
     * Insert or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return bool
     */
    public function updateOrInsert(array $attributes, array $values = [])
    {
        if (! $this->where($attributes)->exists()) {
            return $this->insert(array_merge($attributes, $values));
        }

        if (empty($values)) {
            return true;
        }

        return (bool) $this->take(1)->update($values);
    }

    /**
     * Increment a column's value by a given amount.
     *
     * @param  string  $column
     * @param  float|int  $amount
     * @param  array  $extra
     * @return int
     */
    public function increment($column, $amount = 1, array $extra = [])
    {
        if (! is_numeric($amount)) {
            throw new InvalidArgumentException('Non-numeric value passed to increment method.');
        }

        $wrapped = $this->grammar->wrap($column);

        $columns = array_merge([$column => $this->raw("$wrapped + $amount")], $extra);

        return $this->update($columns);
    }

    /**
     * Decrement a column's value by a given amount.
     *
     * @param  string  $column
     * @param  float|int  $amount
     * @param  array  $extra
     * @return int
     */
    public function decrement($column, $amount = 1, array $extra = [])
    {
        if (! is_numeric($amount)) {
            throw new InvalidArgumentException('Non-numeric value passed to decrement method.');
        }

        $wrapped = $this->grammar->wrap($column);

        $columns = array_merge([$column => $this->raw("$wrapped - $amount")], $extra);

        return $this->update($columns);
    }

    /**
     * Delete a record from the database.
     *
     * @param  mixed  $id
     * @return int
     */
    public function delete($id = null)
    {
        // If an ID is passed to the method, we will set the where clause to check the
        // ID to let developers to simply and quickly remove a single row from this
        // database without manually specifying the "where" clauses on the query.
        if (! is_null($id)) {
            $this->where($this->from.'.id', '=', $id);
        }

        return $this->connection->delete(
            $this->grammar->compileDelete($this), $this->cleanBindings(
                $this->grammar->prepareBindingsForDelete($this->bindings)
            )
        );
    }

    /**
     * Run a truncate statement on the table.
     *
     * @return void
     */
    public function truncate()
    {
        foreach ($this->grammar->compileTruncate($this) as $sql => $bindings) {
            $this->connection->statement($sql, $b