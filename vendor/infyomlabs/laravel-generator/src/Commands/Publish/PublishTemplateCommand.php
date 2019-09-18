;
                case 'text':
                    $field = $this->generateField($column, 'text', 'textarea');
                    break;
                default:
                    $field = $this->generateField($column, 'string', 'text');
                    break;
            }

            if (strtolower($field->name) == 'password') {
                $field->htmlType = 'password';
            } elseif (strtolower($field->name) == 'email') {
                $field->htmlType = 'email';
            } elseif (in_array($field->name, $this->timestamps)) {
                $field->isSearchable = false;
                $field->isFillable = false;
                $field->inForm = false;
                $field->inIndex = false;
            }
            $field->isNotNull = (bool) $column->getNotNull();
            $field->description = $column->getComment(); // get comments from table

            $this->fields[] = $field;
        }
    }

    /**
     * Get primary key of given table.
     *
     * @param string $tableName
     *
     * @return string|null The column name of the (simple) primary key
     */
    public static function getPrimaryKeyOfTable($tableName)
    {
        $schema = DB::getDoctrineSchemaManager();
        $column = $schema->listTableDetails($tableName)->getPrimaryKey();

        return $column ? $column->getColumns()[0] : '';
    }

    /**
     * Get timestamp columns from config.
     *
     * @return array the set of [created_at column name, updated_at column name]
     */
    public static function getTimestampFieldNames()
    {
        if (!config('infyom.laravel_generator.timestamps.enabled', true)) {
            return [];
        }

        $createdAtName = config('infyom.laravel_generator.timestamps.created_at', 'created_at');
        $updatedAtName = config('infyom.laravel_generator.timestamps.updated_at', 'updated_at');
        $deletedAtName = config('infyom.laravel_generator.timestamps.deleted_at', 'deleted_at');

        return [$createdAtName, $updatedAtName, $deletedAtName];
    }

    /**
     * Generates integer text field for database.
     *
     * @param string                       $dbType
     