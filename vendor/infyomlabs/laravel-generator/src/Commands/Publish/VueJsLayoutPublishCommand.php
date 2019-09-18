        $field->parseDBType($dbType.','.$column->getPrecision().','.$column->getScale());
        $field->htmlType = 'number';

        return $this->checkForPrimary($field);
    }

    /**
     * Prepares relations (GeneratorFieldRelation) array from table foreign keys.
     */
    public function prepareRelations()
    {
        $foreignKeys = $this->prepareForeignKeys();
        $this->checkForRelations($foreignKeys);
    }

    /**
     * Prepares foreign keys from table with required details.
     *
     * @return GeneratorTable[]
     */
    public function prepareForeignKeys()
    {
        $tables = $this->schemaManager->listTables();

        $fields = [];

        foreach ($tables as $table) {
            $primaryKey = $table->getPrimaryKey();
            if ($primaryKey) {
                $primaryKey = $primaryKey->getColumns()[0];
            }
            $formattedForeignKeys = [];
            $tableForeignKeys = $table->getForeignKeys();
            foreach ($tableForeignKeys as $tableForeignKey) {
                $generatorForeignKey = new GeneratorForeignKey();
                $generatorForeignKey->name = $tableForeignKey->getName();
                $generatorForeignKey->localField = $tableForeignKey->getLocalColumns()[0];
                $generatorForeignKey->foreignField = $tableForeignKey->getForeignColumns()[0];
                $generatorForeignKey->foreignTable = $tableForeignKey->getForeignTableName();
                $generatorForeignKey->onUpdate = $tableForeignKey->onUpdate();
                $generatorForeignKey->onDelete = $tableForeignKey->onDelete();

                $formattedForeignKeys[] = $generatorForeignKey;
            }

            $generatorTable = new GeneratorTable();
            $generatorTable->primaryKey = $primaryKey;
            $generatorTable->foreignKeys = $formattedForeignKeys;

            $fields[$table->getName()] = $generatorTable;
        }

        return $fields;
    }

    /**
     * Prepares relations array from table foreign keys.
     *
     * @param GeneratorTable[] $tables
     */
    private function checkForRelations($tables)
    {
        // get Model table name and table details from tables list
        $modelTableName = $this->tableName;
        $modelTable = $tables[$modelTableName];
        unset($tables[$modelTableName]);

        $this->relations = [];

        // detects many to one rules for model table
        $manyToOneRelations = $this->detectManyToOne($tables, $modelTable);

        if (count($manyToOneRelations) > 0) {
            $this->relations = array_merge($this->relations, $manyToOneRelations);
        }

        foreach ($tables as $tableName => $table) {
            $foreignKeys = $table->foreignKeys;
            $primary = $table->primaryKey;

            // if foreign key count is 2 then check if many to many relationship is there
            if (count($foreignKeys) == 2) {
                $manyToManyRelation = $this->isManyToMany($tables, $tableName, $modelTable, $modelTableName);
                if ($manyToManyRelation) {
                    $this->relations[] = $manyToManyRelation;
                    continue;
                }
            }

            // iterate each foreign key and check for relationship
            foreach ($foreignKeys as $foreignKey) {
                // check if foreign key is on the model table for which we are using generator command
                if ($foreignKey->foreignTable == $modelTableName) {

                    // detect if one to one relationship is there
                    $isOneToOne = $this->isOneToOne($primary, $foreignKey, $modelTable->primaryKey);
                    if ($isOneToOne) {
                        $modelName = model_name_from_table_name($tableName);
                        $this->relations[] = GeneratorFieldRelation::parseRelation('1t1,'.$modelName);
                        continue;
                    }

                    // detect if one to many relationship is there
                    $isOneToMany = $this->isOneToMany($primary, $foreignKey, $modelTable->primaryKey);
                    if ($isOneToMany) {
                        $modelName = model_name_from_table_name($tableName);
                        $this->relations[] = GeneratorFieldRelation::parseRelation('1tm,'.$modelName);
                        continue;
                    }
                }
            }
        }
    }

    /**
     * Detects many to many relationship
     * If table has only two foreign keys
     * Both foreign keys are primary key in foreign table
     * Also one is from model table and one is from diff table.
     *
     * @param GeneratorTable[] $tables
     * @param string           $tableName
     * @param GeneratorTable   $modelTable
     * @param string           $modelTableName
     *
     * @return bool|GeneratorFieldRelation
     */
    private function isManyToMany($tables, $tableName, $modelTable, $modelTableName)
    {
        // get table details
        $table = $tables[$tableName];

        $isAnyKeyOnModelTable = false;

        // many to many model table name
        $manyToManyTable = '';

        $foreignKeys = $table->foreignKeys;
        $primary = $table->primaryKey;

        // check if any foreign key is there from model table
        foreach ($foreignKeys as $foreignKey) {
            if ($foreignKey->foreignTable == $modelTableName) {
                $isAnyKeyOnModelTable = true;
            }
        }

        // if foreign key is there
        if ($isAnyKeyOnModelTable) {
            foreach ($foreignKeys as $foreignKey) {
                $foreignField = $foreignKey->foreignField;
                $foreignTableName = $foreignKey->foreignTable;

                // if foreign table is model table
                if ($foreignTableName == $modelTableName) {
                    $foreignTable = $modelTable;
                } else {
                    $foreignTable = $tables[$foreignTableName];
                    // get the many to many model table name
                    $manyToManyTable = $foreignTableName;
                }

                // if foreign field is not primary key of foreign table
                // then it can not be many to many
                if ($foreignField != $foreignTable->primaryKey) {
                    return false;
                    break;
                }

                // if foreign field is primary key of this table
                // then it can not be many to many
                if ($foreignField == $primary) {
                    return false;
                }
            }
        }

        $modelName = model_name_from_table_name($manyToManyTable);

        return GeneratorFieldRelation::parseRelation('mtm,'.$modelName.','.$tableName);
    }

    /**
     * Detects if one to one relationship is there
     * If foreign key of table is primary key of foreign table
     * Also foreign key field is primary key of this table.
     *
     * @param string              $primaryKey
     * @param GeneratorForeignKey $foreignKey
     * @param string              $modelTablePrimary
     *
     * @return bool
     */
    private function isOneToOne($primaryKey, $foreignKey, $modelTablePrimary)
    {
        if ($foreignKey->foreignField == $modelTablePrimary) {
            if ($foreignKey->localField == $primaryKey) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detects if one to many relationship is there
     * If foreign key of table is primary key of foreign table
     * Also foreign key field is not primary key of this table.
     *
     * @param string              $primaryKey
     * @param GeneratorForeignKey $foreignKey
     * @param string              $modelTablePrimary
     *
     * @return bool
     */
    private function isOneToMany($primaryKey, $foreignKey, $modelTablePrimary)
    {
        if ($foreignKey->foreignField == $modelTablePrimary) {
            if ($foreignKey->localField != $primaryKey) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect many to one relationship on model table
     * If foreign key of model table is primary key of foreign table.
     *
     * @param GeneratorTable[] $tables
     * @param GeneratorTable   $modelTable
     *
     * @return array
     */
    private function detectManyToOne($tables, $modelTable)
    {
        $manyToOneRelations = [];

        $foreignKeys = $modelTable->foreignKeys;

        foreach ($foreignKeys as $foreignKey) {
            $foreignTable = $foreignKey->foreignTable;
            $foreignField = $foreignKey->foreignField;

            if (!isset($tables[$foreignTable])) {
                continue;
            }

            if ($foreignField == $tables[$foreignTable]->primaryKey) {
                $modelName = model_name_from_table_name($foreignTable);
                $manyToOneRelations[] = GeneratorFieldRelation::parseRelation(
                    'mt1,'.$modelName.','.$foreignKey->localField
                );
            }
        }

        return $manyToOneRelations;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    