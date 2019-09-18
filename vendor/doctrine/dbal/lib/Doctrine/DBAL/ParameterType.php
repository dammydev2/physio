NULLS | OCI_RETURN_LOBS
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fetchAll($fetchMode = null, $fetchArgument = null, $ctorArgs = null)
    {
        $fetchMode = $fetchMode ?: $this->_defaultFetchMode;

        $result = [];

        if ($fetchMode === FetchMode::STANDARD_OBJECT) {
            while ($row = $this->fetch($fetchMode)) {
                $result[] = $row;
            }

            return $result;
        }

        if (! isset(self::$fetchModeMap[$fetchMode])) {
            throw new InvalidArgumentException('Invalid fetch style: ' . $fetchMode);
        }

        if (self::$fetchModeMap[$fetchMode] === OCI_BOTH) {
            while ($row = $this->fetch($fetchMode)) {
                $result[] = $row;
            }
        } else {
            $fetchStructure = OCI_FETCHSTATEMENT_BY_ROW;

            if ($fetchMode === FetchMode::COLUMN) {
                $fetchStructure = OCI_FETCHSTATEMENT_BY_COLUMN;
            }

            // do not try fetching from the statement if it's 