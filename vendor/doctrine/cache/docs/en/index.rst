->transactionNestingLevel;
        }
    }

    /**
     * Creates a new savepoint.
     *
     * @param string $savepoint The name of the savepoint to create.
     *
     * @return void
     *
     * @throws ConnectionException
     */
    public function createSavepoint($savepoint)
    {
        if (! $this->getDatabasePlatform()->supportsSavepoints()) {
            throw ConnectionException::savepointsNotSupported();
        }

        $this->_conn->exec($this->platform->createSavePoint($savepoint));
    }

    /**
     * Releases the given savepoint.
     *
     * @param string $savepoint The name of the savepoint to release.
     *
     * @return void
     *
     * @throws ConnectionException
     */
    public function releaseSavepoint($savepoint)
    {
        if (! $this->getDatabasePlatform()->supportsSavepoints()) {
            throw ConnectionException::savepointsNotSupported();
        }

        if (! $this->platform->supportsReleaseSavepoints()) {
            return;
        }

        $this->_conn->exec($this->platform->releaseSavePoint($savepoint));
    }

    /**
     * Rolls back to the given savepoint.
     *
     * @param string $savepoint The name of the savepoint to rollback to.
     *
     * @return void
     *
     * @throws ConnectionException
     */
    public function rollbackSavepoint($savepoint)
    {
        if (! $this->getDatabasePlatform()->supportsSavepoints()) {
            throw ConnectionException::savepointsNotSupported();
        }

        $this->_conn->exec($this->platform->rollbackSavePoint($savepoint));
    }

    /**
     * Gets the wrapped driver connection.
     *
     * @return \Doctrine\DBAL\Driver\Connection
     */
    public function getWrappedConnection()
    {
        $this->connect();

        return $this->_conn;
    }

    /**
     * Gets the SchemaManager that can be used to inspect or change the
     * database schema through the connection.
     *
     * @return AbstractSchemaManager
     */
    public function getSchemaManager()
    {
        if (! $this->_schemaManager) {
            $this->_schemaManager = $this->_driver->getSchemaManager($this);
        }

        return $this->_schemaManager;
    }

    /**
     * Marks the current transaction so that the only possible
     * outcome for the transaction to be rolled back.
     *
     * @return void
     *
     * @throws ConnectionException If no transaction is active.
     */
    public function setRollbackOnly()
    {
        if ($this->transactionNestingLevel === 0) {
            throw ConnectionException::noActiveTransaction();
        }
        $this->isRollbackOnly = true;
    }

    /**
     * Checks whether the current transaction is marked for rollback only.
     *
     * @return bool
     *
     * @throws ConnectionException If no transaction is active.
     */
    public function isRollbackOnly()
    {
        if ($this->transactionNestingLevel === 0) {
            throw ConnectionException::noActiveTransaction();
        }

        return $this->isRollbackOnly;
    }

    /**
     * Converts a given value to its database representation according to the conversion
     * rules of a specific DBAL mapping type.
     *
     * @param mixed  $value The value to convert.
     * @param string $type  The name of the DBAL mapping type.
     *
     * @return mixed The converted value.
     */
    public function convertToDatabaseValue($value, $type)
    {
        return Type::getType($type)->convertToDatabaseValue($value, $this->getDatabasePlatform());
    }

    /**
     * Converts a given value to its PHP representation according to the conversion
     * rules of a specific DBAL mapping type.
     *
     * @param mixed  $value The value to convert.
     * @param string $type  The name of the DBAL mapping type.
     *
     * @return mixed The converted type.
     */
    public function convertToPHPValue($value, $type)
    {
        return Type::getType($type)->convertToPHPValue($value, $this->getDatabasePlatform());
    }

    /**
     * Binds a set of parameters, some or all of which are typed with a PDO binding type
     * or DBAL mapping type, to a given statement.
     *
     * @internal Duck-typing used on the $stmt parameter to support driver statements as well as
     *           raw PDOStatement instances.
     *
     * @param \Doctrine\DBAL\Driver\Statement $stmt   The statement to bind the values to.
     * @param mixed[]                         $params The map/list of named/positional parameters.
     * @param int[]|string[]                  $types  The parameter types (PDO binding types or DBAL mapping types).
     *
     * @return void
     */
    private function _bindTypedValues($stmt, array $params, array $types)
    {
        // Check whether parameters are positional or named. Mixing is not allowed, just like in PDO.
        if (is_int(key($params))) {
            // Positional parameters
            $typeOffset = array_key_exists(0, $types) ? -1 : 0;
            $bindIndex  = 1;
            foreach ($params as $value) {
                $typeIndex = $bindIndex + $typeOffset;
                if (isset($types[$typeIndex])) {
                    $type                  = $types[$typeIndex];
                    [$value, $bindingType] = $this->getBindingInfo($value, $type);
                    $stmt->bindValue($bindIndex, $value, $bindingType);
                } else {
                    $stmt->bindValue($bindIndex, $value);
                }
                ++$bindIndex;
            }
        } else {
            // Named parameters
            foreach ($params as $name => $value) {
                if (isset($types[$name])) {
                    $type                  = $types[$name];
                    [$value, $bindingType] = $this->getBindingInfo($value, $type);
                    $stmt->bindValue($name, $value, $bindingType);
                } else {
                    $stmt->bindValue($name, $value);
                }
            }
        }
    }

    /**
     * Gets the binding type of a given type. The given type can be a PDO or DBAL mapping type.
     *
     * @param mixed      $value The value to bind.
     * @param int|string $type  The type to bind (PDO or DBAL).
     *
     * @return mixed[] [0] => the (escaped) value, [1] => the binding type.
     */
    private function getBindingInfo($value, $type)
    {
        if (is_string($type)) {
            $type = Type::getType($type);
        }
        if ($type instanceof Type) {
            $value       = $type->convertToDatabaseValue($value, $this->getDatabasePlatform());
            $bindingType = $type->getBindingType();
        } else {
            $bindingType = $type;
        }

        return [$value, $bindingType];
    }

    /**
     * Resolves the parameters to a format which can be displayed.
     *
     * @internal This is a purely internal method. If you rely on this method, you are advised to
     *           copy/paste the code as this method may change, or be removed without prior notice.
     *
     * @param mixed[]        $params
     * @param int[]|string[] $types
     *
   