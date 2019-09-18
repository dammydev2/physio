<?php

namespace Doctrine\DBAL\Event;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use function array_merge;
use function is_array;

/**
 * Event Arguments used when SQL queries for creating table columns are generated inside Doctrine\DBAL\Platform\AbstractPlatform.
 */
class SchemaCreateTableColumnEventArgs extends SchemaEventArgs
{
    /** @var Column */
    private $column;

    /** @var Table */
    private $table;

    /** @var AbstractPlatform */
    private $platform;

    /** @var string[] */
    private $sql = [];

    public function __construct(Column $column, Table $table, AbstractPlatform $platform)
    {
        $this->column   = $column;
        $this->table    = $table;
        $this->platform = $platform;
    }

    /**
     * @return Column
