ql, array $params = [])
    {
        $msg = "An exception occurred while executing '" . $sql . "'";
        if ($params) {
            $msg .= ' with params ' . self::formatParameters($params);
        }
        $msg .= ":\n\n" . $driverEx->getMessage();

        return static::wrapException($driver, $driverEx, $msg);
    }

    /**
     * @param Exception $driverEx
     *
     * @return \Doctrine\DBAL\DBALException
     */
    public static function driverException(Driver $driver, Throwable $driverEx)
    {
        return static::wrapException($driver, $driverEx, 'An exception occurred in driver: ' . $driverEx->getMessage());
    }

    /**
     * @param Exception $driverEx
     *
     * @return \Doctrine\DBAL\DBALException
     */
    private static function wrapException(Driver $driver, Throwable $driverEx, $msg)
    {
        if ($driverEx instanceof DriverException) {
            return $driverEx;
        }
        if ($driver instanceof ExceptionConverterDriver && $driverEx instanceof DriverExceptionInterface) {
            return $driver->convertException($msg, $driverEx);
        }

        return new self($msg, 0, $driverEx);
    }

    /**
     * Returns a human-readable representation of an array of parameters.
     * This properly handles binary data by returning a hex representation.
     *
     * @param mixed[] $params
     *
     * @return string
     */
    private static function formatParameters(array $params)
    {
        return '[' . implode(', ', array_map(static function ($param) {
            if (is_resource($param)) {
                return (string) $param;
            }

            $json = @json_encode($param);

            if (! is_string($json) || $json === 'null' && is_string($param)) {
                // JSON encoding failed, this is not a UTF-8 string.
                return '"\x' . implode('\x', str_split(bin2hex($param), 2)) . '"';
            }

            return $json;
        }, $params)) . ']';
    }

    /**
     * @param string $wrapperClass
     *
     * @return \Doctrine\DBAL\DBALException
     */
    public static function invalidWrapperClass($wrapperClass)
    {
        return new self("The given 'wrapperClass' " . $wrapperClass . ' has to be a ' .
            'subtype of \Doctrine\DBAL\Connection.');
    }

    /**
     * @param string $driverClass
     *
     * @return \Doctrine\DBAL\DBALException
     */
    public static function invalidDriverClass($driverClass)
    {
        return new self("The given 'driverClass' " . $driverClass . ' has to implement the ' . Driver::class . ' interface.');
    }

    /**
     * @param string $tableName
     *
     * @return \Doctrine\DBAL\DBALException
     */
    public static function invalidTableName($tableName)
    {
        return new self('Invalid table na