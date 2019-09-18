aram  array|mixed  $columns
     * @return \Illuminate\Support\Fluent
     */
    public function dropColumn($columns)
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        return $this->addCommand('dropColumn', compact('columns'));
    }

    /**
     * Indicate that the given columns should be renamed.
     *
     * @param  string  $from
     * @param  string  $to
     * @return \Illuminate\Support\Fluent
     */
    public function renameColumn($from, $to)
    {
        return $this->addCommand('renameColumn', compact('from', 'to'));
    }

    /**
     * Indicate that the given primary key should be dropped.
     *
     * @param  string|array  $index
     * @return \Illuminate\Support\Fluent
     */
    public function dropPrimary($index = null)
    {
        return $this->dropIndexCommand('dropPrimary', 'primary', $index);
    }

    /**
     * Indicate that the given unique key should be dropped.
     *
     * @param  string|array  $index
     * @return \Illuminate\Support\Fluent
     */
    public function dropUnique($index)
    {
        return $this->dropIndexCommand('dropUnique', 'unique', $index);
    }

    /**
     * Indicate that the given index should be dropped.
     *
     * @param  string|array  $index
     * @return \Illuminate\Support\Fluent
     */
    public function dropIndex($index)
    {
        return $this->dropIndexCommand('dropIndex', 'index', $index);
    }

    /**
     * Indicate that the given spatial index should be dropped.
     *
     * @param  string|array  $index
     * @return \Illuminate\Support\Fluent
     */
    public function dropSpatialIndex($index)
    {
        return $this->dropIndexCommand('dropSpatialIndex', 'spatialIndex', $index);
    }

    /**
     * Indicate that the given foreign key should be dropped.
     *
     * @param  string|array  $index
     * @return \Illuminate\Support\Fluent
     */
    public function dropForeign($index)
    {
        return $this->dropIndexCommand('dropForeign', 'foreign', $index);
    }

    /**
     * Indicate that the given indexes should be renamed.
     *
     * @param  string  $from
     * @param  string  $to
     * @return \Illuminate\Support\Fluent
     */
    public function renameIndex($from, $to)
    {
        return $this->addCommand('renameIndex', compact('from', 'to'));
    }

    /**
     * Indicate that the timestamp columns should be dropped.
     *
     * @return void
     */
    public function dropTimestamps()
    {
        $this->dropColumn('created_at', 'updated_at');
    }

    /**
     * Indicate that the timestamp columns should be dropped.
     *
     * @return void
     */
    public function dropTimestampsTz()
    {
        $this->dropTimestamps();
    }

    /**
     * Indicate that the soft delete column should be dropped.
     *
     * @param  string  $column
     * @return void
     */
    public function dropSoftDeletes($column = 'deleted_at')
    {
        $this->dropColumn($column);
    }

    /**
     * Indicate that the soft delete column should be dropped.
     *
     * @param  string  $column
     * @return void
     */
    public function dropSoftDeletesTz($column = 'deleted_at')
    {
        $this->dropSoftDeletes($column);
    }

    /**
     * Indicate that the remember token column should be dropped.
     *
     * @return void
     */
    public function dropRememberToken()
    {
        $this->dropColumn('remember_token');
    }

    /**
     * Indicate that the polymorphic columns should be dropped.
     *
     * @param  string  $name
     * @param  string|null  $indexName
     * @return void
     */
    public function dropMorphs($name, $indexName = null)
    {
        $this->dropIndex($indexName ?: $this->createIndexName('index', ["{$name}_type", "{$name}_id"]));

        $this->dropColumn("{$name}_type", "{$name}_id");
    }

    /**
     * Rename the table to a given name.
     *
     * @param  string  $to
     * @return \Illuminate\Support\Fluent
     */
    public function rename($to)
    {
        return $this->addCommand('rename', compact('to'));
    }

    /**
     * Specify the primary key(s) for the table.
     *
     * @param  string|array  $columns
     * @param  string  $name
     * @param  string|null  $algorithm
     * @return \Illuminate\Support\Fluent
     */
    public function primary($columns, $name = null, $algorithm = null)
    {
        return $this->indexCommand('primary', $columns, $name, $algorithm);
    }

    /**
     * Specify a unique index for the table.
     *
     * @param  string|array  $columns
     * @param  string  $name
     * @param  string|null  $algorithm
     * @return \Illuminate\Support\Fluent
     */
    public function unique($columns, $name = null, $algorithm = null)
    {
        return $this->indexCommand('unique', $columns, $name, $algorithm);
    }

    /**
     * Specify an index for the table.
     *
     * @param  string|array  $columns
     * @param  string  $name
     * @param  string|null  $algorithm
     * @return \Illuminate\Support\Fluent
     */
    public function index($columns, $name = null, $algorithm = null)
    {
        return $this->indexCommand('