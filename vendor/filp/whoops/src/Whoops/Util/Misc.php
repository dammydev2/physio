<?php

namespace Faker\ORM\CakePHP;

use Cake\ORM\TableRegistry;

class EntityPopulator
{
    protected $class;
    protected $connectionName;
    protected $columnFormatters = [];
    protected $modifiers = [];

    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * @param string $name
     */
    public function __get($name)
    {
        return $this->{$name};
    }

    /**
     * @param string $name
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    public function mergeColumnFormattersWith($columnFormatters)
    {
        $this->columnFormatters = array_merge($this->columnFormatters, $columnFormatters);
    }

    public function mergeModifiersWith($modifiers)
    {
        $this->modifiers = array_merge($this->modifiers, $modifiers);
    }

    /**
     * @return array
     */
    public function guessColumnFormatters($populator)
    {
        $formatters = [];
        $class = $this->class;
        $table = $this->getTable($class);
        $schema = $table->schema();
        $pk = $schema->primaryKey();
        $guessers = $populator->getGuessers() + ['ColumnTypeGuesser' => new ColumnTypeGuesser($populator->getGenerator())];
        $isForeignKey = function ($column) use ($table) {
            foreach ($table->associations()->type('BelongsTo') as $assoc) {
                if ($column == $assoc->foreignKey()) {
                    return true;
                }
            }
            return false;
        };


        foreach ($schema->columns() as $column) {
            if ($column == $pk[0] || $isForeignKey($column)) {
                continue;
            }

            foreach ($guessers as $guesser) {
                if ($formatter = $guesser->guessFormat($column, $table)) {
                    $formatters[$column] = $formatter;
                    break;
                }
            }
        }

        return $formatters;
    }

    /**
     * @