-------+

TABLE;

        $this->assertEquals($expected, $this->getOutputContent($output));
    }

    public function testBoxedStyleWithColspan()
    {
        $boxed = new TableStyle();
        $boxed
            ->setHorizontalBorderChars('─')
            ->setVerticalBorderChars('│')
            ->setCrossingChars('┼', '┌', '┬', '┐', '┤', '┘', '┴', '└', '├')
        ;

        $table = new Table($output = $this->getOutputStream());
        $table->setStyle($boxed);
        $table
            ->setHeaders(['ISBN', 'Title', 'Author'])
            ->setRows([
                ['99921-58-10-7', 'Divine Comedy', 'Dante Alighieri'],
                new TableSeparator(),
                [new TableCell('This value spans 3 columns.', ['colspan' => 3])],
            ])
        ;
        $table->render();

        $expected =
            <<<TABLE
┌─────�