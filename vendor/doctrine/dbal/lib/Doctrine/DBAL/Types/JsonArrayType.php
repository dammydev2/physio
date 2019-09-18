expression The expression to evaluate
     * @param int $max           Maximum offset for range
     *
     * @return array
     */
    public function getRangeForExpression($expression, $max)
    {
        $values = array();
        $expression = $this->convertLiterals($expression);

        if (strpos($expression, ',') !== false) {
            $ranges = explode(',', $expression);
            $values = [];
            foreach ($ranges as $range) {
                $expanded = $this->getRangeForExpression($range, $this->rangeEnd);
                $values = array_merge($values, $expanded);
            }
            return $values;
        }

        if ($this->isRange($expression) || $this->isIncrementsOfRanges($expression)) {
            if (!$this->isIncrementsOfRanges($expression)) {
                list ($offset, $to) = explode('-', $expression);
                $offset = $this->con