umns()
    {
        return false;
    }

    /**
     * Some platforms need the boolean values to be converted.
     *
     * The default conversion in this implementation converts to integers (false => 0, true => 1).
     *
     * Note: if the input is not a boolean the original input might be returned.
     *
     * There are two contexts when converting booleans: Literals and Prepared Statements.
     * This method should handle the literal case
     *
     * @param mixed $item A boolean or an array of them.
     *
     * @return mixed A boolean database value or an array of them.
     */
    public function convertBooleans($item)
    {
        if (is_array($item)) {
            foreach ($item as $k => $value) {
                if (! is_bool($value)) {
                    continue;
                }

                $item[$k] = (int) $value;
            }
        } elseif (is_bool($item)) {
            $item = (int) $item;
        }

        return $item;
    }

    /**
     * Some platforms have boolean literals that needs to be correctly converted
     *
     * The default conversion tries to convert value into bool "(bool)$item"
     *
     * @param mixed $item
     *
     * @return bool|null
     */
    public function convertFromBoolean($item)
    {
        return $item === null ? null: (bool) $item;
    }

    /**
     * This method should handle the prepared statements case. When there is no
     * distinction, it's OK to use the same method.
     *
     * Note: if the input is not a boolean the original input might be returned.
     *
     * @param mixed $item A boolean or an array of them.
     *
     * @return mixed A boolean database value or an array of them.
     */
    public function convertBooleansToDatabaseValue($item)
    {
        return $this->convertBooleans($item);
    }

    /**
     * Returns the SQL specific for the platform to get the current date.
     *
     * @return string
     */
    public function getCurrentDateSQL()
    {
        return 'CURRENT_DATE';
    }

    /**
     * Returns the SQL specific for the platform to get the current time.
     *
     * @return string
     */
    public function getCurrentTimeSQL()
    {
        return 'CURRENT_TIME';
    }

    /**
     * Returns the SQL specific for the platform to get the current timestamp
     *
     * @return string
     */
    public function getCurrentTimestampSQL()
    {
        return 'CURRENT_TIMESTAMP';
    }

    /**
     * Returns the SQL for a given transaction isolation level Connection constant.
     *
     * @param int $level
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    protected function _getTransactionIsolationLevelSQL($level)
    {
        switch ($level) {
            case TransactionIsolationLevel::READ_UNCOMMITTED:
                return 'READ UNCOMMITTED';
            case TransactionIsolationLevel::READ_COMMITTED:
                return 'READ COMMITTED';
            case TransactionIsolationLevel::REPEATABLE_READ:
                return 'REPEATABLE READ';
            case TransactionIsolationLevel::SERIALIZABLE:
                return 'SERIALIZABLE';
            default:
                throw new InvalidArgumentException('Invalid isolation level:' . $level);
        }
    }

    /**
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getListDatabasesSQL()
    {
    