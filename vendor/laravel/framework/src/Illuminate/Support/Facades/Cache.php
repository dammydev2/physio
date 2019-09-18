<?php

namespace Illuminate\Validation;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Database\ConnectionResolverInterface;

class DatabasePresenceVerifier implements PresenceVerifierInterface
{
    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\ConnectionResolverInterface
     */
    protected $db;

    /**
     * The database connection to use.
     *
     * @var string
     */
    protected $connection;

    /**
     * Create a new database presence verifier.
     *
     * @param  \Illuminate\Database\ConnectionResolverInterface  $db
     * @return void
     */
    public function __construct(ConnectionResolverInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Count the number of objects in a collection having the given value.
     *
     * @param  string  $collection
     * @param  string  $column
     * @param  string  $value
     * @param  int|null  $excludeId
     * @param  string|null  $idColumn
     * @param  array  $extra
     * @return int
     */
    public function getCount($collection, $column, $value, $excludeId = null, $idColumn = null, array $extra = [])
    {
        $query = $this->table($collection)->where($column, '=', $value);

        if (! is_null($excludeId) && $excludeId !== 'NULL') {
            $query->where($idColumn ?: 'id', '<>', $exclude