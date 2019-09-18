 $this->distinctValues)) {
            $this->distinctValues[$attributeName] = $this->extractDistinctValues($attributeName);
        }

        return $this->distinctValues[$attributeName];
    }

    /**
     * Extract the distinct values from the data.
     *
     * @param  string  $attribute
     * @return array
     */
    protected function extractDistinctValues($attribute)
    {
        $attributeData = ValidationData::extractDataFromPath(
            ValidationData::getLeadingExplicitAttributePath($attribute), $this->data
        );

        $pattern = str_replace('\*', '[^.]+', preg_quote($attribute, '#'));

        return Arr::where(Arr::dot($attributeData), function ($value, $key) use ($pattern) {
            return (bool) preg_match('#^'.$pattern.'\z#u', $key);
        });
    }

    /**
     * Validate that an attribute is a valid e-mail address.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function validateEmail($attribute, $value)
    {
        if (! is_string($value) && ! (is_object($value) && method_exists($value, '__toString'))) {
            return false;
        }

        return (new EmailValidator)->isValid($value, new RFCValidation);
    }

    /**
     * Validate the existence of an attribute value in a database table.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateExists($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'exists');

        [$connection, $table] = $this->parseTable($parameters[0]);

        // The second parameter position holds the name of the column that should be
        // verified as existing. If this parameter is not specified we will guess
        // that the columns being "verified" shares the given attribute's name.
        $column = $this->getQueryColumn($parameters, $attribute);

        $expected = is_array($value) ? count(array_unique($value)) : 1;

        return $this->getExistCount(
            $connection, $table, $column, $value, $parameters
        ) >= $expected;
    }

    /**
     * Get the number of records that exist in storage.
     *
     * @param  mixed   $connection
     * @param  string  $table
     * @param  string  $column
     * @param  mixed   $value
     * @param  array   $parameters
     * @return int
     */
    protected function getExistCount($connection, $table, $column, $value, $parameters)
    {
        $verifier = $this->getPresenceVerifierFor($connection);

        $extra = $this->getExtraConditions(
            array_values(array_slice($parameters, 2))
        );

        if ($this->currentRule instanceof Exists) {
            $extra = array_merge($extra, $this->currentRule->queryCallbacks());
        }

        return is_array($value)
                ? $verifier->getMultiCount($table, $column, $value, $extra)
                : $verifier->getCount($table, $column, $value, null, null, $extra);
    }

    /**
     * Validate the uniqueness of an attribute value on a given database table.
     *
     * If a database column is not specified, the attribute will be used.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateUnique($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'unique');

        [$connection, $table] = $this->parseTable($parameters[0]);

        // The second parameter position holds the name of the column that needs to
        // be verified as unique. If this parameter isn't specified we will just
        // assume that this column to be verified shares the attribute's name.
        $column = $this->getQueryColumn($parameters, $attribute);

        [$idColumn, $id] = [null, null];

        if (isset($parameters[2])) {
            [$idColumn, $id] = $this->getUniqueIds($parameters);

            if (! is_null($id)) {
                $id = stripslashes($id);
            }
        }

        // The presence verifier is responsible for counting rows within this store
        // mechanism which might be a relational database or any other permanent
        // data store like Redis, etc. We will use it to determine uniqueness.
        $verifier = $this->getPresenceVerifierFor($connection);

        $extra = $this->getUniqueExtra($parameters);

        if ($this->currentRule instanceof Unique) {
            $extra = array_merge($extra, $this->currentRule->queryCallbacks());
        }

        return $verifier->getCount(
            $table, $column, $value, $id, $idColumn, $extra
        ) == 0;
    }

    /**
     * Get the excluded ID column and value for the unique rule.
     *
     * @param  array  $parameters
     * @return array
     */
    protected function getUniqueIds($parameters)
    {
        $idColumn = $parameters[3] ?? 'id';

        return [$idColumn, $this->prepareUniqueId($parameters[2])];
    }

    /**
     * Prepare the given ID for querying.
     *
     * @param  mixed  $id
     * @return int
     */
    protected function prepareUniqueId($id)
    {
        if (preg_match('/\[(.*)\]/', $id, $matches)) {
            $id = $this->getValue($matches[1]);
        }

        if (strtolower($id) === 'null') {
            $id = null;
        }

        if (filter_var($id, FILTER_VALIDATE_INT) !== false) {
            $id = (int) $id;
        }

        return $id;
    }

    /**
     * Get the extra conditions for a unique rule.
     *
     * @param  array  $parameters
     * @return array
     */
    protected function getUniqueExtra($parameters)
    {
        if (isset($parameters[4])) {
            return $this->getExtraConditions(array_slice($parameters, 4));
        }

        return [];
    }

    /**
     * Parse the connection / table for the unique / exists rules.
     *
     * @param  string  $table
     * @return array
     */
    public function parseTable($table)
    {
        return Str::contains($table, '.') ? explode('.', $table, 2) : [null, $table];
    }

    /**
     * Get the column name for an exists / unique query.
     *
     * @param  array  $parameters
     * @param  string  $attribute
     * @return bool
     */
    public function getQueryColumn($parameters, $attribute)
    {
        return isset($parameters[1]) && $parameters[1] !== 'NULL'
                    ? $parameters[1] : $this->guessColumnForQuery($attribute);
    }

    /**
     * Guess the database column from the given attribute name.
     *
     * @param  string  $attribute
     * @return string
     */
    public function guessColumnForQuery($attribute)
    {
        if (in_array($attribute, Arr::collapse($this->implicitAttributes))
                && ! is_numeric($last = last(explode('.', $attribute)))) {
            return $last;
        }

        return $attribute;
    }

    /**
     * Get the extra conditions for a unique / exists rule.
     *
     * @param  array  $segments
     * @return array
     */
    protected function getExtraConditions(array $segments)
    {
        $extra = [];

        $count = count($segments);

 