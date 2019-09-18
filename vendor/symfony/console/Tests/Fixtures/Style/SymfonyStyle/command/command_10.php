       $expected =
<<<'TABLE'
+-------+
| 12345 |
+-------+

TABLE;

        $this->assertEquals($expected, $this->getOutputContent($output));
    }

    public function testTableCellWithNumericFloatValue()
    {
        $table = new Table($output = $this->getOutputStream());

        $table->setRows([[new TableCell(12345.01)]]);
        $table->render();

        $expected =
<<<'TABLE'
+----------+
| 12345.01 |
+----------+

TABLE;

        $this->assertEquals($expected, $this->getOutputContent($output));
    }

    public function testStyle()
    {
        $style = new TableStyle();
        $style
            ->setHorizontalBorderChars('.')
            ->setVerticalBorderChars('.')
            ->setDefaultCrossingChar('.')
        ;

        Table::setStyleDefinition('dotfull', $style);
        $table = new Table($output = $this->getOutputStream());
        $table
            ->setHeaders(['Foo'])
            ->setRows