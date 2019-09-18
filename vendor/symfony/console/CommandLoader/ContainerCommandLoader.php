     $rows = array_merge($this->headers, [$divider = new TableSeparator()], $this->rows);
        $this->calculateNumberOfColumns($rows);

        $rows = $this->buildTableRows($rows);
        $this->calculateColumnsWidth($rows);

        $isHeader = true;
        $isFirstRow = false;
        foreach ($rows as $row) {
            if ($divider === $row) {
                $isHeader = false;
                $isFirstRow = true;

                continue;
            }
            if ($row instanceof TableSeparator) {
                $this->renderRowSeparator();

                continue;
            }
            if (!$row) {
                continue;
            }

            if ($isHeader || $isFirstRow) {
                if ($isFirstRow) {
                    $this->renderRowSeparator(self::SEPARATOR_TOP_BOTTOM);
                    $isFirstRow = false;
                } else {
                    $this->renderRowSeparator(self::SEPARATOR_TOP, $this->headerTitle, $this->style->getHeaderTitleFormat());
                }
            }

            $this->renderRow($row, $isHeader ? $this->style->getCellHeaderFormat() : $this->style->getCellRowFormat());
        }
        $this->renderRowSeparator(self::SEPARATOR_BOTTOM, $this->footerTitle, $this->style->getFooterTitleFormat());

        $this->cleanup();
        $this->rendered = true;
    }

    