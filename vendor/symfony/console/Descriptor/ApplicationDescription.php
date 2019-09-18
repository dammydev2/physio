      foreach (range($column + 1, $column + $cell->getColspan() - 1) as $position) {
                    // insert empty value at column position
                    $newRow[] = '';
                }
            }
        }

        return $newRow ?: $row;
    }

    private function copyRow(array $rows, int $line): array
    {
        $row = $rows[$line];
        foreach ($row as $cellKey => $cellValue) {
            $row[$cellKey] = '';
            if ($cellValue instanceof TableCell) {
                $row[$cellKey] = new TableCell('', ['colspan' => $cellValue->getColspan()]);
            }
        }

        return $row;
    }

    /**
     * Gets number of columns by row.
     */
    private function getNumberOfColumns(array $row): int
    {
        $columns = \count($row);
        foreach ($row as $column) {
            $columns += $column instanceof TableCell ? ($column->getColspan() - 1) : 0;
        }

        return $columns;
    }

    /**
     * Gets list of columns for the given row.
     */
    private function getRowColumns(array $row): array
    {
        $columns = range(0, $this->numberOfColumns - 1);
        foreach ($row as $cellKey => $cell) {
            if ($cell instanceof TableCell && $cell->getColspan() > 1) {
                // exclude grouped columns.
                $columns = array_diff($columns, range($cellKey + 1, $cellKey + $cell->getColspan() - 1));
            }
        }

        return $columns;
    }

    /**
     * Calculates columns widths.
     */
    private function calculateColumnsWidth(iterable $rows)
    {
        for ($column = 0; $column < $this->numberOfColumns; ++$column) {
            $lengths = [];
            foreach ($rows as $row) {
                if ($row instanceof TableSeparator) {
                    continue;
                }

                foreach ($row as $i => $cell) {
                    if ($cell instanceof TableCell) {
                        $textContent = Helper::removeDecoration($this->output->getFormatter(), $cell);
                        $textLength = Helper::strlen($textContent);
                        if ($textLength > 0) {
                            $contentColumns = str_split($textContent, ceil($textLength / $cell->getColspan()));
                            foreach ($contentColumns as $position => $content) {
                                $row[$i + $position] = $content;
                            }
                        }
                    }
                }

                $lengths[] = $this->getCellWidth($row, $column);
            }

            $this->effectiveColumnWidths[$column] = max($lengths) + Helper::strlen($this->style->getCellRowContentFormat()) - 2;
        }
    }

    private function getColumnSeparatorWidth(): int
    {
        return Helper::strlen(sprintf($this->style->getBorderFormat(), $this->style->getBorderChars()[3]));
    }

    private function getCellWidth(array $row, int $column): int
    {
        $cellWidth = 0;

        if (isset($row[$column])) {
            $cell = $row[$column];
            $cellWidth = Helper::strlenWithoutDecoration($this->output->getFormatter(), $cell);
        }

        $columnWidth = isset($this->columnWidths[$column]) ? $this->columnWidths[$column] : 0;
        $cellWidth = max($cellWidth, $columnWidth);

        return isset($this->columnMaxWidths[$column]) ? min($this->columnMaxWidths[$column], $cellWidth) : $cellWidth;
    }

    /**
     * Called after rendering to cleanup cache data.
     */
    private function cleanup()
    {
        $this->effectiveColumnWidths = [];
        $this->numberOfColumns = null;
    }

    private static function initStyles()
    {
        $bo