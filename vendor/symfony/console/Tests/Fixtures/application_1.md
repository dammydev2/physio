public function testFormatToStringObject()
    {
        $formatter = new OutputFormatter(false);
        $this->assertEquals(
            'some info', $formatter->format(new TableCell())
        );
    }

    public function testNotDecoratedFormatter()
    {
        $formatter = new OutputFormatter(false);

        $this->assertTrue($formatter->hasStyle('error'));
        $this->assertTrue($formatter->hasStyle('info'));
        $this->assertTrue($formatter->hasStyle('comment'));
        $this->assertTrue($formatter->hasStyle('question'));

        $this->assertEquals(
            'some error', $formatter->format('<error>some error</error>')
        );
        $this->assertEquals(
            'some info', $formatter->format('<info>some info</info>')
        );
        $this->assertEquals(
            'some comment', $formatter->format('<comment>some comment</comment>')
        );
        $this->assertEquals(
            'some question', $formatter->format('<question>some question</question>')
        );
        $this->assertEquals(
            'some text with inline style', $formatter->format('<fg=red>some text with inline style</>')
        );

        $formatter->setDecorated(true);

        $this->assertEquals(
            "\033[37;41msome error\033[39;49m", $formatter->format('<error>some error</error>')
        );
        $this->assertEquals(
            "\033[32msome info\033[39m", $formatter->format('<info>some info</info>')
        );
        $this->assertEquals(
            "\033[33msome comment\033[39m", $formatter->format('<comment>some comment</comment>')
        );
        $this->assertEquals(
            "\033[30;46msome question\033[39;49m", $formatter->format('<question>some question</question>')
        );
        $this->assertEquals(
            "\033[31msome text with inline style\033[39m", $formatter->format('<fg=red>some text with inline style</>')
        );
    }

    public function testContentWithLineBreaks()
    {
        $formatter = new OutputFormatter(true);

        $this->assertEquals(<<<EOF
\033[32m
some text\033[39m
EOF
            , $formatter->format(<<<'EOF'
<info>
some text</info>
EOF
        ));

        $this->assertEquals(<<<EOF
\033[32msome text
\033[39m
EOF
            , $formatter->format(<<<'EOF'
<info>some text
</info>
EOF
        ));

        $this->assertEquals(<<<EOF
\033[32m
some text
\033[39m
EOF
            , $formatter->format(<<<'EOF'
<info>
some text
</info>
EOF
        ));

        $this->assertEquals(<<<EOF
\033[32m
some text
more text
\033[39m
EOF
            , $formatter->format(<<<'EOF'
<info>
some text
more text
</info>
EOF
        ));
    }

    public function testFormatAndWrap()
