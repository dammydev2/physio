orderChars();

        return sprintf($this->style->getBorderFormat(), self::BORDER_OUTSIDE === $type ? $borders[1] : $borders[3]);
    }

    /**
     * Renders table row.
     *
     * Example:
     *
     *     | 9971-5-0210-0 | A Tale of Two Cities  | Charles Dickens  |
     */
    private function renderRow(array $row, string $cellFormat)
    {
        $rowContent = $this->renderColumnSeparator(self::BORDER_OUTSIDE);
        $columns = $this->getRowColumns($row);
        $last = \count($columns) - 1;
        foreach ($columns as $i => $column) {
            $rowContent .= $this->renderCell($row, $column, $cellFormat);
            $rowContent .= $this->renderColumnSeparator($last === $i ? self::BORDER_OUTSIDE : self::BORDER_INSIDE);
        }
        $this->output->writeln($rowContent);
    }

    /**
     * Renders table cell with padding.
     */
    private function renderCell(array $row, int $column, string $cellFormat)
    {
        $cell = isset($row[$column]) ? $row[$column] : '';
        $width = $this->effectiveColumnWidths[$column];
        if ($cell instanceof TableCell && $cell->getColspan() > 1) {
            // add the width of the following columns(numbers of colspan).
            foreach (range($column + 1, $column + $cell->getColspan() - 1) as $nextColumn) {
                $width += $this->ge