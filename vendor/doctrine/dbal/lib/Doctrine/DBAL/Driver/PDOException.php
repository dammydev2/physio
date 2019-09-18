<?php

namespace Doctrine\DBAL\Event;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Table;
use InvalidArgumentException;
use function is_string;

/**
 * Event Arguments used when the SQL query for dropping tables are generated inside Doctrine\DBAL\Platform\AbstractPlatform.
 */
class SchemaDropTableEventArgs extends SchemaEventArgs
{
    /** @var string|Table */
    private $table;

    /** @var AbstractPlatform */
    private $platform;

    /** @var string|null */
    private $sql = null;

    /**
     * @param string|Table $table
     *
     * @throws InvalidArgumentException
     */
    public function __construct($table, AbstractPlatform $platform)
    {
        if (! $table instanceof Table && ! is_string($table)) {
            throw new InvalidArgumentException('SchemaDropTableEventArgs expects $table parameter to be string or \Doctrine\DBAL\Schema\Table.');
        }

        $this->table    = $table;
        $this->platform = $platform;
    }

    /**
     * @return string|Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return AbstractPlatform