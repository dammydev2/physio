<?php

namespace Doctrine\DBAL\Event;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Column;

/**
 * Event Arguments used when the portable column definition is generated inside Doctrine\DBAL\Schema\AbstractSchemaManager.
 */
class SchemaColumnDefinitionEventArgs extends SchemaEventArgs
{
    /** @var Column|null */
    private $column = null;

    /**
     * Raw column data as fetched from the database.
     *
     * @var mixed[]
     */
    private $tableColumn;

    /** @var string */
    private $table;

    /** @var string */
    private $database;

    /** @var Connection */
    private $connection;

    /**
     * @param mixed[] $tableColumn
     * @param string  $table
     * @param string  $database
     */
    public function __construct(array $tableColumn, $table, $database, Connection $connection)
    {
        $this->tableColumn = $tableColu