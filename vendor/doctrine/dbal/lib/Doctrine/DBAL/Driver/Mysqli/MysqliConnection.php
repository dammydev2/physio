e)
    {
        if ($this->doctrineTypeMapping === null) {
            $this->initializeAllDoctrineTypeMappings();
        }

        $dbType = strtolower($dbType);

        return isset($this->doctrineTypeMapping[$dbType]);
    }

    /**
     * Initializes the Doctrine Type comments instance variable for in_array() checks.
     *
     * @return void
     */
    protected function initializeCommentedDoctrineTypes()
    {
        $this->doctrineTypeComments = [];

        foreach (Type::getTypesMap() as $typeName => $className) {
            $type = Type::getType($typeName);

            if (! $type->requiresSQLCommentHint($this)) {
                continue;
            }

            $this->doctrineTypeComments[] = $typeName;
        }
    }

    /**
     * Is it necessary for the platform to add a parsable type comment to allow reverse engineering the given type?
     *
     * @return bool
     */
    public function isCommentedDoctrineType(Type $doctrineType)
    {
        if ($this->doctrineTypeComments === null) {
            $this->initializeCommentedDoctrineTypes();
        }

        return in_array($doctrineType->getName(), $this->doctrineTypeComments);
    }

    /**
     * Marks this type as to be commented in ALTER TABLE and CREATE TABLE statements.
     *
     * @param string|Type $doctrineType
     *
     * @return void
     */
    public function markDoctrineTypeCommented($doctrineType)
    {
        if ($this->doctrineTypeComments === null) {
            $this->initializeCommentedDoctrineTypes();
        }

        $this->doctrineTypeComments[] = $doctrineType instanceof Type ? $doctrineType->getName() : $doctrineType;
    }

    /**
     * Gets the comment to append to a column comment that helps parsing this type in reverse engineering.
     *
     * @return string
     */
    public function getDoctrineTypeComment(Type $doctrineType)
    {
        return '(DC2Type:' . $doctrineType->getName() . ')';
    }

    /**
     * Gets the comment of a passed column modified by potential doctrine type comment hints.
     *
     * @return string
     */
    protected function getColumnComment(Column $column)
    {
        $comment = $column->getComment();

        if ($this->isCommentedDoctrineType($column->getType())) {
            $comment .= $this->getDoctrineTypeComment($column->getType());
        }

        return $comment;
    }

    /**
     * Gets the character used for identifier quoting.
     *
     * @return string
     */
    public function getIdentifierQuoteCharacter()
    {
        return '"';
    }

    /**
     * Gets the string portion that starts an SQL comment.
     *
     * @return string
     */
    public function getSqlCommentStartString()
    {
        return '--';
    }

    /**
     * Gets the string portion that ends an SQL comment.
     *
     * @return string
     */
    public function getSqlCommentEndString()
    {
        return "\n";
    }

    /**
     * Gets the maximum length of a char field.
     */
    public function getCharMaxLength() : int
    {
        return $this->getVarcharMaxLength();
    }

    /**
     * Gets the maximum length of a varchar field.
     *
     * @return int
     */
    public function getVarcharMaxLength()
    {
        return 4000;
    }

    /**
     * Gets the default length of a varchar field.
     *
     * @return int
     */
    public function getVarcharDefaultLength()
    {
        return 255;
    }

    /**
     * Gets the maximum length of a binary field.
     *
     * @return int
     */
    public function getBinaryMaxLength()
    {
        return 4000;
    }

    /**
     * Gets the default length of a binary field.
     *
     * @return int
     */
    public function getBinaryDefaultLength()
    {
        return 255;
    }

    /**
     * Gets all SQL wildcard characters of the platform.
     *
     * @return string[]
     */
    public function getWildcards()
    {
        return ['%', '_'];
    }

    /**
     * Returns the regular expression operator.
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getRegexpExpression()
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Returns the global unique identifier expression.
     *
     * @deprecated Use application-generated UUIDs instead
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getGuidExpression()
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Returns the SQL snippet to get the average value of a column.
     *
     * @param string $column The column to use.
     *
     * @return string Generated SQL including an AVG aggregate function.
     */
    public function getAvgExpression($column)
    {
        return 'AVG(' . $column . ')';
    }

    /**
     * Returns the SQL snippet to get the number of rows (without a NULL value) of a column.
     *
     * If a '*' is used instead of a column the number of selected rows is returned.
     *
     * @param string|int $column The column to use.
     *
     * @return string Generated SQL including a COUNT aggregate function.
     */
    public function getCountExpression($column)
    {
        return 'COUNT(' . $column . ')';
    }

    /**
     * Returns the SQL snippet to get the highest value of a column.
     *
     * @param string $column The column to use.
     *
     * @return string Generated SQL including a MAX aggregate function.
     */
    public function getMaxExpression($column)
    {
        return 'MAX(' . $column . ')';
    }

    /**
     * Returns the SQL snippet to get the lowest value of a column.
     *
     * @param string $column The column to use.
     *
     * @return string Generated SQL including a MIN aggregate function.
     */
    public function getMinExpression($column)
    {
        return 'MIN(' . $column . ')';
    }

    /**
     * Returns the SQL snippet to get the total sum of a column.
     *
     * @param string $column The column to use.
     *
     * @return string Generated SQL including a SUM aggregate function.
     */
    public function getSumExpression($column)
    {
        return 'SUM(' . $column . ')';
    }

    // scalar functions

    /**
     * Returns the SQL snippet to get the md5 sum of a field.
     *
     * Note: Not SQL92, but common functionality.
     *
     * @param string $column
     *
     * @return string
     */
    public function getMd5Expression($column)
    {
        return 'MD5(' . $column . ')';
    }

    /**
     * Returns the SQL snippet to get the length of a text field.
     *
     * @param string $column
     *
     * @return string
     */
    public function getLengthExpression($column)
    {
        return 'LENGTH(' . $column . ')';
    }

    /**
     * Returns the SQL snippet to get the squared value of a column.
     *
     * @param string $column The column to use.
     *
     * @return string Generated SQL including an SQRT aggregate function.
     */
    public function getSqrtExpression($column)
    {
        return 'SQRT(' . $column . ')';
    }

    /**
     * Returns the SQL snippet to round a numeric field to the number of decimals specified.
     *
     * @param string $column
     * @param int    $decimals
     *
     * @return string
     */
    public function getRoundExpression($column, $decimals = 0)
    {
        return 'ROUND(' . $column . ', ' . $decimals . ')';
    }

    /**
     * Returns the SQL snippet to get the remainder of the division operation $expression1 / $expression2.
     *
     * @param string $expres