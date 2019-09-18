 function doWrite($sessionId, $data)
    {
        $maxlifetime = (int) ini_get('session.gc_maxlifetime');

        try {
            // We use a single MERGE SQL query when supported by the database.
            $mergeStmt = $this->getMergeStatement($sessionId, $data, $maxlifetime);
            if (null !== $mergeStmt) {
                $mergeStmt->execute();

                return true;
            }

            $updateStmt = $this->getUpdateStatement($sessionId, $data, $maxlifetime);
            $updateStmt->execute();

            // When MERGE is not supported, like in Postgres < 9.5, we have to use this approach that can result in
            // duplicate key errors when the same session is written simultaneously (given the LOCK_NONE behavior).
            // We can just catch such an error and re-execute the update. This is similar to a serializable
            // transaction with retry logic on serialization failures but without the overhead and without possible
            // false positives due to longer gap locking.
            if (!$updateStmt->rowCount()) {
                try {
                    $insertStmt = $this->getInsertStatement($sessionId, $data, $maxlifetime);
                    $insertStmt->execute();
                } catch (\PDOException $e) {
                    // Handle integrity violation SQLSTATE 23000 (or a subclass like 23505 in Postgres) for duplicate keys
                    if (0 === strpos($e->getCode(), '23')) {
                        $updateStmt->execute();
                    } else {
                        throw $e;
                    }
                }
            }
        } catch (\PDOException $e) {
            $this->rollback();

            throw $e;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function updateTimestamp($sessionId, $data)
    {
        $maxlifetime = (int) ini_get('session.gc_maxlifetime');

        try {
            $updateStmt = $this->pdo->prepare(
                "UPDATE $this->table SET $this->lifetimeCol = :lifetime, $this->timeCol = :time WHERE $this->idCol = :id"
            );
            $updateStmt->bindParam(':id', $sessionId, \PDO::PARAM_STR);
            $updateStmt->bindParam(':lifetime', $maxlifetime, \PDO::PARAM_INT);
            $updateStmt->bindValue(':time', time(), \PDO::PARAM_INT);
            $updateStmt->execute();
        } catch (\PDOException $e) {
            $this->rollback();

            throw $e;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->commit();

        while ($unlockStmt = array_shift($this->unlockStatements)) {
            $unlockStmt->execute();
        }

        if ($this->gcCalled) {
            $this->gcCalled = false;

            // delete the session records that have expired
            if ('mysql' === $this->driver) {
                $sql = "DELETE FROM $this->table WHERE $this->lifetimeCol + $this->timeCol < :time";
            } else {
                $sql = "DELETE FROM $this->table WHERE $this->lifetimeCol < :time - $this->timeCol";
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':time', time(), \PDO::PARAM_INT);
            $stmt->execute();
        }

        if (false !== $this->dsn) {
            $this->pdo = null; // only close lazy-connection
        }

        return true;
    }

    /**
     * Lazy-connects to the database.
     *
     * @param string $dsn DSN string
     */
    private function connect($dsn)
    {
        $this->pdo = new \PDO($dsn, $this->username, $this->password, $this->connectionOptions);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->driver = $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
    }

    /**
     * Builds a PDO DSN from a URL-like connection string.
     *
     * @param string $dsnOrUrl
     *
     * @return string
     *
     * @todo implement missing support for oci DSN (which look totally different from other PDO ones)
     */
    private function buildDsnFromUrl($dsnOrUrl)
    {
        // (pdo_)?sqlite3?:///... => (pdo_)?sqlite3?://localhost/... or else the URL will be in