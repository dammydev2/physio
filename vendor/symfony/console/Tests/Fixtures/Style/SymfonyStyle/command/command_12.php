$table = new Table($output = $this->getOutputStream());
        $table
            ->setHeaders(['ISBN', 'Title', 'Author', 'Price'])
            ->setRows([
                ['99921-58-10-7', 'Divine Comedy', 'Dante Alighieri', '9.95'],
                ['9971-5-0210-0', 'A Tale of Two Cities', 'Charles Dickens', '139.25'],
            ])
            ->setColumnWidth(0, 15)
            ->setColumnWidth(3, 10);

        $style = new TableStyle();
        $style->setPadType(STR_PAD_LEFT);
        $table->setColumnStyle(3, $style);

        $table->render();

        $expected =
            <<<TABLE
+-----------------+----------------------+-----------------+------------+
| ISBN            | Title                | Author          |      Price |
+-----------------+----------------------+-----------------+------------+
| 99921-58-10-7   | Divine Comedy        | Dante Alighieri