<?php

namespace Illuminate\Database;

use Closure;
use Exception;
use Throwable;
use Illuminate\Database\Schema\SqlServerBuilder;
use Doctrine\DBAL\Driver\PDOSqlsrv\Driver as DoctrineDriver;
use Illuminate\Database\Query\Processors\SqlServerProcessor;
use Illuminate\Database\Query\Grammars\SqlServerGrammar as QueryGrammar;
use Illuminate\Database\Schema\Grammars\SqlServerGrammar as SchemaGrammar;

class SqlServerConnection extends Connection
{
    /**
     * Execute a Closure within a transaction.
     *
     * @param  \Closure  $callback
     * @param  int  $attempts
     * @return mixed
     *
     * @throws \Exception|\Throwable
     */
    public function transaction(Closure $callback, $attempts = 1)
    {
        for ($a = 1; $a <= $attempts; $a++) {
            if ($this->getDriverName() === 'sqlsrv') {
                return parent::transaction($callback);
   