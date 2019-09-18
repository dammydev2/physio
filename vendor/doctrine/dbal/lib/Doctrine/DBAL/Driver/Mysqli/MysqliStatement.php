ts to space.
     *
     * @return string
     */
    public function getTrimExpression($str, $mode = TrimMode::UNSPECIFIED, $char = false)
    {
        $expression = '';

        switch ($mode) {
            case TrimMode::LEADING:
                $expression = 'LEADING ';
                break;

            case TrimMode::TRAILING:
                $expression = 'TRAILING ';
                break;

            case TrimMode::BOTH:
                $expression = 'BOTH ';
                break;
        }

        if ($char !== false) {
            $expression .= $char . ' ';
        }

        if ($mode || $char !== false) {
            $expression .= 'FROM ';
        }

        return 'TRIM(' . $expression . $str . ')';
    }

    /**
     * Returns the SQL snippet to trim trailing space characters from the expression.
     *
     * @param string $str Literal string or column name.
     *
     * @return string
     */
    public function getRtrimExpression($str)
    {
        return 'RTRIM(' . $str . ')';
    }

    /**
     * Returns the SQL snippet to trim leading space characters from the expression.
     *
     * @param string $str Literal string or column name.
     *
     * @return string
     */
    public function getLtrimExpression($str)
    {
        return 'LTRIM(' . $str . ')';
    }

    /**
     * Returns the SQL snippet to change all characters from the expression to uppercase,
     * according to the current character set mapping.
     *
     * @param string $str Literal string or column name.
     *
     * @return string
     */
    public function getUpperExpression($str)
    {
        return 'UPPER(' . $str . ')';
    }

    /**
     * Returns the SQL snippet to change all characters from the expression to lowercase,
     * according to the current character set mapping.
     *
     * @param string $str Literal string or column name.
     *
     * @return string
     */
    public function getLowerExpression($str)
    {
        return 'LOWER(' . $str . ')';
    }

    /**
     * Returns the SQL snippet to get the position of the first occurrence of substring $substr in string $str.
     *
     * @param string   $str      Literal string.
     * @param string   $substr   Literal string to find.
     * @param int|bool $startPos Position to start at, beginning of string by default.
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getLocateExpression($str, $substr, $startPos = false)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Returns the SQL snippet to get the current system date.
     *
     * @return string
     */
    public function getNowExpression()
    {
        return 'NOW()';
    }

    /**
     * Returns a SQL snippet to get a substring inside an SQL statement.
     *
     * Note: Not SQL92, but common functionality.
     *
     * SQLite only supports the 2 parameter variant of this function.
     *
     * @param string   $value  An sql string literal or column name/alias.
     * @param int      $from   Where to start the substring portion.
     * @param int|null $length The substring portion length.
     *
     * @return string
     */
    public function getSubstringExpression($value, $from, $length = null)
    {
        if ($length === null) {
            return 'SUBSTRING(' . $value . ' FROM ' . $from . ')';
        }

        return 'SUBSTRING(' . $value . ' FROM ' . $from . ' FOR ' . $length . ')';
    }

    /**
     * Returns a SQL snippet to concatenate the given expressions.
     *
     * Accepts an arbitrary number of string parameters. Each parameter must contain an expression.
     *
     * @return string
     */
    public function getConcatExpression()
    {
        return implode(' || ', func_get_args());
    }

    /**
     * Returns the SQL for a logical not.
     *
     * Example:
     * <code>
     * $q = new Doctrine_Query();
     * $e = $q->expr;
     * $q->select('*')->from('table')
     *   ->where($e->eq('id', $e->not('null'));
     * </code>
     *
     * @param string $expression
     *
     * @return string The logical expression.
     */
    public function getNotExpression($expression)
    {
        return 'NOT(' . $expression . ')';
    }

    /**
     * Returns the SQL that checks if an expression is null.
     *
     * @param string $expression The expression that should be compared to null.
     *
     * @return string The logical expression.
     */
    public function getIsNullExpression($expression)
    {
        return $expression . ' IS NULL';
    }

    /**
     * Returns the SQL that checks if an expression is not null.
     *
     * @param string $expression The expression that should be compared to null.
     *
     * @return string The logical expression.
     */
    public function getIsNotNullExpression($expression)
    {
        return $expression . ' IS NOT NULL';
    }

    /**
     * Returns the SQL that checks if an expression evaluates to a value between two values.
     *
     * The parameter $expression is checked if it is between $value1 and $value2.
     *
     * Note: There is a slight difference in the way BETWEEN works on some databases.
     * http://www.w3schools.com/sql/sql_between.asp. If you want complete database
     * independence you should avoid using between().
     *
     * @param string $expression The value to compare to.
     * @param string $value1     The lower value to compare with.
     * @param string $value2     The higher value to compare with.
     *
     * @return string The logical expression.
     */
    public function getBetweenExpression($expression, $value1, $value2)
    {
        return $expression . ' BETWEEN ' . $value1 . ' AND ' . $value2;
    }

    /**
     * Returns the SQL to get the arccosine of a value.
     *
     * @param string $value
     *
     * @return string
     */
    public function getAcosExpression($value)
    {
        return 'ACOS(' . $value . ')';
    }

    /**
     * Returns the SQL to get the sine of a value.
     *
     * @param string $value
     *
     * @return string
     */
    public function getSinExpression($value)
    {
        return 'SIN(' . $value . ')';
    }

    /**
     * Returns the SQL to get the PI value.
     *
     * @return string
     */
    public function getPiExpression()
    {
        return 'PI()';
    }

    /**
     * Returns the SQL to get the cosine of a value.
     *
     * @param string $value
     *
     * @return string
     */
    public function getCosExpression($value)
    {
        return 'COS(' . $value . ')';
    }

    /**
     * Returns the SQL to calculate the difference in days between the two passed dates.
     *
     * Computes diff = date1 - date2.
     *
     * @param string $date1
     * @param string $date2
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateDiffExpression($date1, $date2)
    {
        throw DBALException::notSupported(__METHOD__);
    }

    /**
     * Returns the SQL to add the number of given seconds to a date.
     *
     * @param string $date
     * @param int    $seconds
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateAddSecondsExpression($date, $seconds)
    {
        return $this->getDateArithmeticIntervalExpression($date, '+', $seconds, DateIntervalUnit::SECOND);
    }

    /**
     * Returns the SQL to subtract the number of given seconds from a date.
     *
     * @param string $date
     * @param int    $seconds
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateSubSecondsExpression($date, $seconds)
    {
        return $this->getDateArithmeticIntervalExpression($date, '-', $seconds, DateIntervalUnit::SECOND);
    }

    /**
     * Returns the SQL to add the number of given minutes to a date.
     *
     * @param string $date
     * @param int    $minutes
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateAddMinutesExpression($date, $minutes)
    {
        return $this->getDateArithmeticIntervalExpression($date, '+', $minutes, DateIntervalUnit::MINUTE);
    }

    /**
     * Returns the SQL to subtract the number of given minutes from a date.
     *
     * @param string $date
     * @param int    $minutes
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateSubMinutesExpression($date, $minutes)
    {
        return $this->getDateArithmeticIntervalExpression($date, '-', $minutes, DateIntervalUnit::MINUTE);
    }

    /**
     * Returns the SQL to add the number of given hours to a date.
     *
     * @param string $date
     * @param int    $hours
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateAddHourExpression($date, $hours)
    {
        return $this->getDateArithmeticIntervalExpression($date, '+', $hours, DateIntervalUnit::HOUR);
    }

    /**
     * Returns the SQL to subtract the number of given hours to a date.
     *
     * @param string $date
     * @param int    $hours
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateSubHourExpression($date, $hours)
    {
        return $this->getDateArithmeticIntervalExpression($date, '-', $hours, DateIntervalUnit::HOUR);
    }

    /**
     * Returns the SQL to add the number of given days to a date.
     *
     * @param string $date
     * @param int    $days
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateAddDaysExpression($date, $days)
    {
        return $this->getDateArithmeticIntervalExpression($date, '+', $days, DateIntervalUnit::DAY);
    }

    /**
     * Returns the SQL to subtract the number of given days to a date.
     *
     * @param string $date
     * @param int    $days
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateSubDaysExpression($date, $days)
    {
        return $this->getDateArithmeticIntervalExpression($date, '-', $days, DateIntervalUnit::DAY);
    }

    /**
     * Returns the SQL to add the number of given weeks to a date.
     *
     * @param string $date
     * @param int    $weeks
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateAddWeeksExpression($date, $weeks)
    {
        return $this->getDateArithmeticIntervalExpression($date, '+', $weeks, DateIntervalUnit::WEEK);
    }

    /**
     * Returns the SQL to subtract the number of given weeks from a date.
     *
     * @param string $date
     * @param int    $weeks
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateSubWeeksExpression($date, $weeks)
    {
        return $this->getDateArithmeticIntervalExpression($date, '-', $weeks, DateIntervalUnit::WEEK);
    }

    /**
     * Returns the SQL to add the number of given months to a date.
     *
     * @param string $date
     * @param int    $months
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateAddMonthExpression($date, $months)
    {
        return $this->getDateArithmeticIntervalExpression($date, '+', $months, DateIntervalUnit::MONTH);
    }

    /**
     * Returns the SQL to subtract the number of given months to a date.
     *
     * @param string $date
     * @param int    $months
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateSubMonthExpression($date, $months)
    {
        return $this->getDateArithmeticIntervalExpression($date, '-', $months, DateIntervalUnit::MONTH);
    }

    /**
     * Returns the SQL to add the number of given quarters to a date.
     *
     * @param string $date
     * @param int    $quarters
     *
     * @return string
     *
     * @throws DBALException If not supported on this platform.
     */
    public function getDateAddQuartersExpression($date, $quarters)
    {
        return $this->getDateArithmeticIntervalExpression($date, '+', $quarters, DateIntervalUnit::QUARTER);
    }

    /**
     * Returns the SQL to subtract the 