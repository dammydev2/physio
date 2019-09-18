
        $command->algorithm = 'gist';

        return $this->compileIndex($blueprint, $command);
    }

    /**
     * Compile a foreign key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileForeign(Blueprint $blueprint, Fluent $command)
    {
        $sql = parent::compileForeign($blueprint, $command);

        if (! is_null($command->deferrable)) {
            $sql .= $command->deferrable ? ' deferrable' : ' not deferrable';
        }

        if ($command->deferrable && ! is_null($command->initiallyImmediate)) {
            $sql .= $command->initiallyImmediate ? ' initially immediate' : ' initially deferred';
        }

        if (! is_null($command->notValid)) {
            $sql .= ' not valid';
        }

        return $sql;
    }

    /**
     * Compile a drop table command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDrop(Blueprint $blueprint, Fluent $command)
    {
        return 'drop table '.$this->wrapTable($blueprint);
    }

    /**
     * Compile a drop table (if exists) command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropIfExists(Blueprint $blueprint, Fluent $command)
    {
        return 'drop table if exists '.$this->wrapTable($blueprint);
    }

    /**
     * Compile the SQL needed to drop all tables.
     *
     * @param  string  $tables
     * @return string
     */
    public function compileDropAllTables($tables)
    {
        return 'drop table "'.implode('","', $tables).'" cascade';
    }

    /**
     * Compile the SQL needed to drop all views.
     *
     * @param  string  $views
     * @return string
     */
    public function compileDropAllViews($views)
    {
        return 'drop view "'.implode('","', $views).'" cascade';
    }

    /**
     * Compile the SQL needed to retrieve all table names.
     *
     * @param  string  $schema
     * @return string
     */
    public function compileGetAllTables($schema)
    {
        return "select tablename from pg_catalog.pg_tables where schemaname = '{$schema}'";
    }

    /**
     * Compile the SQL needed to retrieve all view names.
     *
     * @param  string  $schema
     * @return string
     */
    public function compileGetAllViews($schema)
    {
        return "select viewname from pg_catalog.pg_views where schemaname = '{$schema}'";
    }

    /**
     * Compile a drop column command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropColumn(Blueprint $blueprint, Fluent $command)
    {
        $columns = $this->prefixArray('drop column', $this->wrapArray($command->columns));

        return 'alter table '.$this->wrapTable($blueprint).' '.implode(', ', $columns);
    }

    /**
     * Compile a drop primary key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropPrimary(Blueprint $blueprint, Fluent $command)
    {
        $index = $this->wrap("{$blueprint->getTable()}_pkey");

        return 'alter table '.$this->wrapTable($blueprint)." drop constraint {$index}";
    }

    /**
     * Compile a drop uni