umn index
     *
     * @return TableStyle
     */
    public function getColumnStyle($columnIndex)
    {
        return $this->columnStyles[$columnIndex] ?? $this->getStyle();
    }

    /**
     * Sets the minimum width of a column.
     *
     * @param int $columnIndex Column index
     * @param int $width       Minimum column width in characters
     *
     * @return $this
     */
    public function setColumnWidth($columnIndex, $width)
    {
        $this->columnWidths[(int) $columnIndex] = (int) $width;

        return $this;
    }

    /**
     * Sets the minimum width of all columns.
     *
     * @param array $widths
     *
     * @return $this
     */
    public function setColu