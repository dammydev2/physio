<?php

namespace Faker\ORM\CakePHP;

class ColumnTypeGuesser
{
    protected $generator;

    public function __construct(\Faker\Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @return \Closure|null
     */
    public function guessFormat($column, $table)
    {
        $generator = $this->generator;
        $schema = $table->schema();

        switch ($schema->columnType($column)) {
            case 'boolean':
                return function () use ($generator) {
                    return $generator->boolean;
                };
            case 'integer':
                return function () {
                    return mt_rand(0, intval('2147483647'));
                