<?php

namespace InfyOm\Generator\Utils;

use DB;
use InfyOm\Generator\Common\GeneratorField;
use InfyOm\Generator\Common\GeneratorFieldRelation;

class GeneratorForeignKey
{
    /** @var string */
    public $name;
    public $localField;
    public $foreignField;
    public $foreignTable;
    public $onUpdate;
    public $onDelete;
}

class GeneratorTable
{
    /** @var string */
    public $primaryKey;

    /** @var GeneratorForeignKey[] */
    public $foreignKeys;
}

class TableFieldsGenerator
{
    /** @var string */
    public $tableName;
    public $primaryKey;

    /** @var bool */
    public $defaultSearchable;

    /** @var array */
    public $timestamps;

    /** @var \Doctrine\DBAL\Schema\AbstractSchemaManager */
    private $schemaManager;

    /** @var \Doctrine\DBAL\Schema\Column[] */
    private $columns;

    /** @var GeneratorField[] */
    public $fields;

    /** @var GeneratorFieldRelation[] */
    public $relations;

    /** @var array */
    public $ignoredFields;

    public function __construct($tableName, $ignoredFields)
    {
        $this->tableName = $tableName;
        $this->ignoredFields = $ignoredFields;

        $this->schemaManager = DB::getDoctrineSchemaManager();
        $platform = $this->schemaManager->getDatabasePlatform();
        $defaultMappings = [
            'enum' => 'string',
            'json' => 'text',
            'bit'  => 'boolean',
        ];

        $mappings = config('infyom.larav