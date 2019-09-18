READ COMMITTED');
                }
                $this->pdo->beginTransaction();
            }
            $this->inTransaction = true;
        }
    }

    /**
     * Helper method to commit a transaction.
     */
    private function commit()
    {
        if ($this->inTransaction) {
            try {
                // commit read-write transaction which also releases the lock
                if ('sqlite' === $this->driver) {
                    $this->pdo->exec('COMMIT');
                } else {
                    $this->pdo->commit();
                }
                $this->inTransaction = false;
            } catch (\PDOException $e) {
                $this->rollback();

                throw $e;
            }
        }
    }

    /**
     * Helper method to rollback a transaction.
     */
    private function rollback()
    {
        // We only need to rollback if we are in a transaction. Otherwise the resulting
        // error would hide the real problem why rollback was called. We might not be
        // in a transaction when not using the transactional locking behavior or when
        // two callbacks (e.g. destroy and write) are invoked that both fail.
        if ($this->inTransaction) {
            if ('sqlite' === $this->driver) {
                $this->pdo->exec('ROLLBACK');
            } else {
                $this->pdo->rollBack();
            }
            $this->inTransaction = false;
        }
    }

    /**
     * Reads the session data in respect to the different locking strategies.
     *
     * We need to make sure we do not return session data that is already considered garbage according
     * to the session.gc_maxlifetime setting because gc() is called after read() and only sometimes.
     *
     * @param string $sessionId Session ID
     *
     * @return string The session data
     */
    protected function doRead($sessionId)
    {
        if (self::LOCK_ADVISORY === $this->lockMode) {
            $this->unlockStatements[] = $this->doAdvisoryLock($sessionId);
        }

        $selectSql = $this->getSelectSql();
        $selectStmt = $this->pdo->prepare($selectSql);
        $selectStmt->bindParam(':id', $sessionId, \PDO::PARAM_STR);
        $insertStmt = null;

        do {
            $selectStmt->execute();
            $sessionRows = $selectStmt->fetchAll(\PDO::FETCH_NUM);

            if ($sessionRows) {
                if ($sessionRows[0][1] + $sessionRows[0][2] < time()) {
                    $this->sessionExpired = true;

                    return '';
                }

                return \is_resource($sessionRows[0][0]) ? stream_get_contents($sessionRows[0][0]) : $sessionRows[0][0];
            }

            if (null !== $insertStmt) {
                $this->rollback();
                throw new \RuntimeException('Failed to read session: INSERT reported a duplicate id but next SELECT did not return any data.');
            }

            if (!filter_var(ini_get('session.use_strict_mode'), FILTER_VALIDATE_BOOLEAN) && self::LOCK_TRANSACTIONAL === $this->lockMode && 'sqlite' !== $this->driver) {
                // In strict mode, session fixation is not possible: new sessions always start with a unique
                // random id, so that concurrency is not possible and this code path can be skipped.
                // Exclusive-reading of non-existent rows does not block, so we need to do an insert to block
                // until other connections to the session are committed.
                try {
                    $insertStmt = $this->getInsertStatement($sessionId, '', 0);
                    $insertStmt->execute();
                } catch (\PDOException $e) {
                    // Catch duplicate key error because other connection created the session already.
                    // It would only not be the case when the other connection destroyed the session.
                    if (0 === strpos($e->getCode(), '23')) {
                        // Retrieve finished session data written by concurrent connection by restarting the loop.
                        // We have to start a new transaction as a failed query will mark the current transaction as
                        // aborted in PostgreSQL and disallow further queries within it.
                        $this->rollback();
                        $this->beginTransaction();
                        continue;
                    }

                    throw $e;
                }
            }

            return '';
        } while (true);
    }

    /**
     * Executes an application-level lock on the database.
     *
     * @return \PDOStatement The statement that needs to be executed later to release the lock
     *
     * @throws \DomainException When an unsupported PDO driver is used
     *
     * @todo implement missing advisory locks
     *       - for oci using DBMS_LOCK.REQUEST
     *       - for sqlsrv using sp_getapplock with LockOwner = Session
     */
    private function doAdvisoryLock(string $sessionId)
    {
        switch ($this->driver) {
            case 'mysql':
                // MySQL 5.7.5 and later enforces a maximum length on lock names of 64 characters. Previously, no limit was enforced.
                $lockId = \substr($sessionId, 0, 64);
                // should we handle the return value? 0 on timeout, null on error
                // we use a timeout of 50 seconds which is also the default for innodb_lock_wait_timeout
                $stmt = $this->pdo->prepare('SELECT GET_LOCK(:key, 50)');
                $stmt->bindValue(':key', $lockId, \PDO::PARAM_STR);
                $stmt->execute();

                $releaseStmt = $this->pdo->prepare('DO RELEASE_LOCK(:key)');
                $releaseStmt->bindValue(':key', $lockId, \PDO::PARAM_STR);

                return $releaseStmt;
            case 'pgsql':
                // Obtaining an exclusive session level advisory lock requires an integer key.
                // When session.sid_bits_per_character > 4, the session id can contain non-hex-characters.
                // So we cannot just use hexdec().
                if (4 === \PHP_INT_SIZE) {
                    $sessionInt1 = $this->convertStringToInt($sessionId);
                    $sessionInt2 = $this->convertStringToInt(substr($sessionId, 4, 4));

                    $stmt = $this->pdo->prepare('SELECT pg_advisory_lock(:key1, :key2)');
                    $stmt->bindValue(':key1', $sessionInt1, \PDO::PARAM_INT);
                    $stmt->bindValue(':key2', $sessionInt2, \PDO::PARAM_INT);
                    $stmt->execute();

                    $releaseStmt = $this->pdo->prepare('SELECT pg_advisory_unlock(:key1, :key2)');
                    $releaseStmt->bindValue(':key1', $sessionInt1, \PDO::PARAM_INT);
                    $releaseStmt->bindValue(':key2', $sessionInt2, \PDO::PARAM_INT);
                } else {
                    $sessionBigInt = $this->convertStringToInt($sessionId);

                    $stmt = $this->pdo->prepare('SELECT pg_advisory_lock(:key)');
                    $stmt->bindValue(':key', $sessionBigInt, \PDO::PARAM_INT);
                    $stmt->execute();

                    $releaseStmt = $this->pdo->prepare('SELECT pg_advisory_unlock(:key)');
                    $releaseStmt->bindValue(':key', $sessionBigInt, \PDO::PARAM_INT);
                }

                return $releaseStmt;
            case 'sqlite':
                throw new \DomainException('SQLite does not support advisory locks.');
            default:
                throw new \DomainException(sprintf('Advisory locks are currently not implemented for PDO driver "%s".', $this->driver));
        }
    }

    /**
     * Encodes the first 4 (when PHP_INT_SIZE == 4) or 8 characters of the string as an integer.
     *
     * Keep in mind, PHP integers are signed.
     */
    private function convertStringToInt(string $string): int
    {
        if (4 === \PHP_INT_SIZE) {
            return (\ord($string[3]) << 24) + (\ord($string[2]) << 16) + (\ord($string[1]) << 8) + \ord($string[0]);
        }

        $int1 = (\ord($string[7]) << 24) + (\ord($string[6]) << 16) + (\ord($string[5]) << 8) + \ord($string[4]);
        $int2 = (\ord($string[3]) << 24) + (\ord($string[2]) << 16) + (\ord($string[1]) << 8) + \ord($string[0]);

        return $int2 + ($int1 << 32);
    }

    /**
     * Return a locking or nonlocking SQL query to read session information.
     *
     * @throws \DomainException When an unsupported PDO driver is used
     */
    private function getSelectSql(): string
    {
        if (self::LOCK_TRANSACTIONAL === $this->lockMode) {
            $this->beginTransaction();

            switch ($this->driver) {
                case 'mysql':
                case 'oci':
                case 'pgsql':
                    return "SELECT $this->dataCol, $this->lifetimeCol, $this->timeCol FROM $this->table WHERE $this->idCol = :id FOR UPDATE";
                case 'sqlsrv':
                    return "SELECT $this->dataCol, $this->lifetimeCol, $this->timeCol FROM $this->table WITH (UPDLOCK, ROWLOCK) WHERE $this->idCol = :id";
                case 'sqlite':
                    // we already locked when starting transaction
                    break;
                default:
                    throw new \DomainException(sprintf('Transactional locks are currently not implemented for PDO driver "%s".', $this->driver));
            }
        }

        return "SELECT $this->dataCol, $this->lifetimeCol, $this->timeCol FROM $this->table WHERE $this->idCol = :id";
    }

    /**
     * Returns an insert statement supported by the database for writing session data.
     *
     * @param string $sessionId   Session ID
     * @param string $sessionData Encoded session data
     * @param int    $maxlifetime session.gc_maxlifetime
     *
     * @return \PDOStatement The insert statement
     */
    private function getInsertStatement($sessionId, $sessionData, $maxlifetime)
    {
        switch ($this->driver) {
            case 'oci':
                $data = fopen('php://memory', 'r+');
                fwrite($data, $sessionData);
                rewind($data);
                $sql = "INSERT INTO $this->table ($this->idCol, $this->dataCol, $this->lifetimeCol, $this->timeCol) VALUES (:id, EMPTY_BLOB(), :lifetime, :time) RETURNING $this->dataCol into :data";
                break;
            default:
                $data = $sessionData;
                $sql = "INSERT INTO $this->table ($this->idCol, $this->dataCol, $this->lifetimeCol, $this->timeCol) VALUES (:id, :data, :lifetime, :time)";
                break;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $sess