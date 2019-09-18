* @param  bool  $autoIncrement
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function unsignedSmallInteger($column, $autoIncrement = false)
    {
        return $this->smallInteger($column, $autoIncrement, true);
    }

    /**
     * Create a new unsigned medium integer (3-byte) column on the table.
     *
     * @param  string  $column
     * @param  bool  $autoIncrement
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function unsignedMediumInteger($column, $autoIncrement = false)
    {
        return $this->mediumInteger($column, $autoIncrement, true);
    }

    /**
     * Create a new unsigned big integer (8-byte) column on the table.
     *
     * @param  string  $column
     * @param  bool  $autoIncrement
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function unsignedBigInteger($column, $autoIncrement = false)
    {
        return $this->bigInteger($column, $autoIncrement, true);
    }

    /**
     * Create a new float column on the table.
     *
     * @param  string  $column
     * @param  int  $total
     * @param  int  $places
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function float($column, $total = 8, $places = 2)
    {
        return $this->addColumn('float', $column, compact('total', 'places'));
    }

    /**
     * Create a new double column on the table.
     *
     * @param  string  $column
     * @param  int|null  $total
     * @param  int|null  $places
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function double($column, $total = null, $places = null)
    {
        return $this->addColumn('double', $column, compact('total', 'places'));
    }

    /**
     * Create a new decimal column on the table.
     *
     * @param  string  $column
     * @param  int  $total
     * @param  int  $places
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function decimal($column, $total = 8, $places = 2)
    {
        return $this->addColumn('decimal', $column, compact('total', 'places'));
    }

    /**
     * Create a new unsigned decimal column on the table.
     *
     * @param  string  $column
     * @param  int  $total
     * @param  int  $places
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function unsignedDecimal($column, $total = 8, $places = 2)
    {
        return $this->addColumn('decimal', $column, [
            'total' => $total, 'places' => $places, 'unsigned' => true,
        ]);
    }

    /**
     * Create a new boolean column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function boolean($column)
    {
        return $this->addColumn('boolean', $column);
    }

    /**
     * Create a new enum column on the table.
     *
     * @param  string  $column
     * @param  array  $allowed
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function enum($column, array $allowed)
    {
        return $this->addColumn('enum', $column, compact('allowed'));
    }

    /**
     * Create a new set column on the table.
     *
     * @param  string  $column
     * @param  array  $allowed
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function set($column, array $allowed)
    {
        return $this->addColumn('set', $column, compact('allowed'));
    }

    /**
     * Create a new json column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function json($column)
    {
        return $this->addColumn('json', $column);
    }

    /**
     * Create a new jsonb column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function jsonb($column)
    {
        return $this->addColumn('jsonb', $column);
    }

    /**
     * Create a new date column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function date($column)
    {
        return $this->addColumn('date', $column);
    }

    /**
     * Create a new date-time column on the table.
     *
     * @param  string  $column
     * @param  int  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function dateTime($column, $precision = 0)
    {
        return $this->addColumn('dateTime', $column, compact('precision'));
    }

    /**
     * Create a new date-time column (with time zone) on the table.
     *
     * @param  string  $column
     * @param  int  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function dateTimeTz($column, $precision = 0)
    {
        return $this->addColumn('dateTimeTz', $column, compact('precision'));
    }

    /**
     * Create a new time column on the table.
     *
     * @param  string  $column
     * @param  int  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function time($column, $precision = 0)
    {
        return $this->addColumn('time', $column, compact('precision'));
    }

    /**
     * Create a new time column (with time zone) on the table.
     *
     * @param  string  $column
     * @param  int  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function timeTz($column, $precision = 0)
    {
        return $this->addColumn('timeTz', $column, compact('precision'));
    }

    /**
     * Create a new timestamp column on the table.
     *
     * @param  string  $column
     * @param  int  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function timestamp($column, $precision = 0)
    {
        return $this->addColumn('timestamp', $column, compact('precision'));
    }

    /**
     * Create a new timestamp (with time zone) column on the table.
     *
     * @param  string  $column
     * @param  int  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function timestampTz($column, $precision = 0)
    {
        return $this->addColumn('timestampTz', $column, compact('precision'));
    }

    /**
     * Add nullable creation and update timestamps to the table.
     *
     * @param  int  $precision
     * @return void
     */
    public function timestamps($precision = 0)
    {
        $this->timestamp('created_at', $precision)->nullable();

        $this->timestamp('updated_at', $precision)->nullable();
    }

    /**
     * Add nullable creation and update timestamps to the table.
     *
     * Alias for self::timestamps().
     *
     * @param  int  $precision
     * @return void
     */
    public function nullableTimestamps($precision = 0)
    {
        $this->timestamps($precision);
    }

    /**
     * Add creation and update timestampTz columns to the table.
     *
     * @param  int  $precision
     * @return void
     */
    public function timestampsTz($precision = 0)
    {
        $this->timestampTz('created_at', $precision)->nullable();

        $this->timestampTz('updated_at', $precision)->nullable();
    }

    /**
     * Add a "deleted at" timestamp for the table.
     *
     * @param  string  $column
     * @param  int  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function softDeletes($column = 'deleted_at', $precision = 0)
    {
        return $this->timestamp($column, $precision)->nullable();
    }

    /**
     * Add a "deleted at" timestampTz for the table.
     *
     * @param  string  $column
     * @param  int  $precision
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function softDeletesTz($column = 'deleted_at', $precision = 0)
    {
        return $this->timestampTz($column, $precision)->nullable();
    }

    /**
     * Create a new year column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function year($column)
    {
        return $this->addColumn('year', $column);
    }

    /**
     * Create a new binary column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function binary($column)
    {
        return $this->addColumn('binary', $column);
    }

    /**
     * Create a new uuid column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function uuid($column)
    {
        return $this->addColumn('uuid', $column);
    }

    /**
     * Create a new IP address column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function ipAddress($column)
    {
        return $this->addColumn('ipAddress', $column);
    }

    /**
     * Create a new MAC address column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function macAddress($column)
    {
        return $this->addColumn('macAddress', $column);
    }

    /**
     * Create a new geometry column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function geometry($column)
    {
        return $this->addColumn('geometry', $column);
    }

    /**
     * Create a new point column on the table.
     *
     * @param  string  $column
     * @param  int|null  $srid
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function point($column, $srid = null)
    {
        return $this->addColumn('point', $column, compact('srid'));
    }

    /**
     * Create a new linestring column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function lineString($column)
    {
        return $this->addColumn('linestring', $column);
    }

    /**
     * Create a new polygon column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function polygon($column)
    {
        return $this->addColumn('polygon', $column);
    }

    /**
     * Create a new geometrycollection column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function geometryCollection($column)
    {
        return $this->addColumn('geometrycollection', $column);
    }

    /**
     * Create a new multipoint column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function multiPoint($column)
    {
        return $this->addColumn('multipoint', $column);
    }

    /**
     * Create a new multilinestring column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function multiLineString($column)
    {
        return $this->addColumn('multilinestring', $column);
    }

    /**
     * Create a new multipolygon column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function multiPolygon($column)
    {
        return $this->addColumn('multipolygon', $column);
    }

    /**
     * Create a new generated, computed column on the table.
     *
     * @param  string  $column
     * @param  string  $expression
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function computed($column, $expression)
    {
        return $this->addColumn('computed', $column, compact('expression'));
    }

    /**
     * Add the proper columns for a polymorphic table.
     *
     * @param  string  $name
     * @param  string|null  $indexName
     * @return void
     */
    public function morphs($name, $indexName = null)
    {
        $this->string("{$name}_type");

        $this->unsignedBigInteger("{$name}_id");

        $this->index(["{$name}_type", "{$name}_id"], $indexName);
    }

    /**
     * Add nullable columns for a polymorphic table.
     *
     * @param  string  $name
     * @param  string|null  $indexName
     * @return void
     */
    public function nullableMorphs($name, $indexName = null)
    {
        $this->string("{$name}_type")->nullable();

        $this->unsignedBigInteger("{$name}_id")->nullable();

        $this->index(["{$name}_type", "{$name}_id"], $indexName);
    }

    /**
     * Adds the `remember_token` column to the table.
     *
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function rememberToken()
    {
        return $this->string('remember_token', 100)->nullable();
    }

    /**
     * Add a new index command to the blueprint.
     *
     * @param  string  $type
     * @param  string|array  $columns
     * @param  string  $index
     * @param  string|null  $algorithm
     * @return \Illuminate\Support\Fluent
     */
    protected function indexCommand($type, $columns, $index, $algorithm = null)
    {
        $columns = (array) $columns;

        // If no name was specified for this index, we will create one using a basic
        // convention of the table name, followed by the columns, followed by an
        // index type, such as primary or index, which makes the index unique.
        $index = $index ?: $this->createIndexName($type, $columns);

        return $this->addCommand(
            $type, compact('index', 'columns', 'algorithm')
        );
    }

    /**
     * Create a new drop index command on the blueprint.
     *
     * @param  string  $command
     * @param  string  $type
     * @param  string|array  $index
     * @return \Illuminate\Support\Fluent
     */
    protected function dropIndexCommand($command, $type, $index)
    {
        $columns = [];

        // If the given "index" is actually an array of columns, the developer means
        // to drop an index merely by specifying the columns involved without the
        // conventional name, so we will build the index name from the columns.
        if (is_array($index)) {
            $index = $this->createIndexName($type, $columns = $index);
        }

        return $this->indexCommand($command, $columns, $index);
    }

    /**
     * Create a default index name for the table.
     *
     * @param  string  $type
     * @param  array  $columns
     * @return string
     */
    protected function createIndexName($type, array $columns)
    {
        $index = strtolower($this->prefix.$this->table.'_'.implode('_', $columns).'_'.$type);

        return str_replace(['-', '.'], '_', $index);
    }

    /**
     * Add a new column to the blueprint.
     *
     * @param  string  $type
     * @param  string  $name
     * @param  array  $parameters
     * @return \Illuminate\Database\Schema\ColumnDefinition
     */
    public function addColumn($type, $name, array $parameters = [])
    {
        $this->columns[] = $column = new ColumnDefinition(
            array_merge(compact('type', 'name'), $parameters)
        );

        return $column;
    }

    /**
     * Remove a column from the schema blueprint.
     *
     * @param  string  $name
     * @return $this
     */
    public function removeColumn($name)
    {
        $this->columns = array_values(array_filter($this->columns, function ($c) use ($name) {
            return $c['name'] != $name;
        }));

        return $this;
    }

    /**
     * Add a new command to the blueprint.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @return \Illuminate\Support\Fluent
     */
    protected function addCommand($name, array $parameters = [])
    {
        $this->commands[] = $command = $this->createCommand($name, $parameters);

        return $command;
    }

    /**
     * Create a new Fluent command.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @return \Illuminate\Support\Fluent
     */
    protected function createCommand($name, array $parameters = [])
    {
        return new Fluent(array_merge(compact('name'), $parameters));
    }

    /**
     * Get the table the blueprint describes.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Get the columns on the blueprint.
     *
     * @return \Illuminate\Database\Schema\ColumnDefinition[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get the commands on the blueprint.
     *
     * @return \Illuminate\Sup