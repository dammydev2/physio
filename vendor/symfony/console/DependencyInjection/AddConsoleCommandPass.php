    $rows[$rowKey][$column] = $line;
                    } else {
                        $unmergedRows[$rowKey][$lineKey][$column] = $line;
                    }
                }
            }
        }

        return new TableRows(function () use ($rows, $unmergedRows) {
            foreach ($rows as $rowKey => $row) {
                yield $this->fillCells($row);

                if (isset($unmergedRows[$rowKey])) {
                    foreach ($unmergedRows[$rowKey] as $row) {
                        yield $row;
                    }
                }
            }
        });
    }

    private function calculateRowCount(): int
    {
        $numberOfRows = \count(iterator_to_array($this->buildTableRows(array_merge($this->headers, [new TableSeparator()], $this->rows))));

        if ($this->headers) {
            ++$numberOfRows; // Add row for header separator
        }

        ++$numberOfRows; // Add row for footer separator

        return $numberOfRows;
    }

    /**
     * fill rows that contains rowspan > 1.
     *
     * @throws InvalidArgumentException
     */
    private function fillNextRows(array $rows, int $line): array
    {
        $unmergedRows = [];
        foreach ($rows[$line] as $column => $cell) {
            if (null !== $cell && !$cell instanceof TableCell && !is_scalar($cell) && !(\is_object($cell) && method_exists($cell, '__toString'))) {
                throw new InvalidArgumentException(sprintf('A cell must be a TableCell, a scalar or an object implementing __toString, %s given.', \gettype($cell)));
            }
            if ($cell instanceof TableCell && $cell->getRowspan() > 1) {
                $nbLines = $cell->getRowspan() - 1;
                $lines = [$cell];
                if (strstr($cell, "\n")) {
                    $lines = explode("\n", str_replace("\n", "<fg=default;bg=default>\n</>", $cell));
                    $nbLines = \count($lines) > $nbLines ? substr_count($cell, "\n") : $nbLines;

                    $rows[$line][$column] = new TableCell($lines[0], ['colspan' => $cell->getColspan()]);
                    unset($lines[0]);
                }

                // create a two dimensional array (rowspan x colspan)
                $unmergedRows = array_replace_recursive(array_fill($line + 1, $nbLines, []), $unmergedRows);
                foreach ($unmergedRows as $unmergedRowKey => $unmergedRow) {
                    $value = isset($lines[$unmergedRowKey - $line]) ? $lines[$unmergedRowKey - $line] : '';
                    $unmergedRows[$unmergedRowKey][$column] = new TableCell($value, ['colspan' => $cell->getColspan()]);
                    if ($nbLines === $unmergedRowKey - $line) {
                        break;
                    }
                }
            }
        }

        foreach ($unmergedRows as $unmergedRowKey => $unmergedRow) {
            // we need to know if $unmergedRow will be merged or inserted into $rows
            if (isset($rows[$unmergedRowKey]) && \is_array($rows[$unmergedRowKey]) && ($this->getNumberOfColumns($rows[$unmergedRowKey]) + $this->getNumberOfColumns($unmergedRows[$unmergedRowKey]) <= $this->numberOfColumns)) {
                foreach ($unmergedRow as $cellKey => $cell) {
                    // insert cell into row at cellKey position
                    array_splice($rows[$unmergedRowKey], $cellKey, 0, [$cell]);
                }
            } else {
                $row = $this->copyRow($rows, $unmergedRowKey - 1);
                foreach ($unmergedRow as $column => $cell) {
                    if (!empty($cell)) {
                        $row[$column] = $unmer