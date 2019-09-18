ons(), '->parse() parses empty array options as null ("--option=value" syntax)');

        $input = new ArgvInput(['cli.php', '--name', 'foo', '--name', 'bar', '--name', '--anotherOption']);
        $input->bind(new InputDefinition([
            new InputOption('name', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY),
            new InputOption('anotherOption', null, InputOption::VALUE_NONE),
        ]));
        $this->assertSame(['name' => ['foo', 'bar', null], 'anotherOption' => true], $input->getOptions(), '->parse() parses empty array options ("--option value" syntax)');
    }

    public function testParseNegativeNumberAfterDoubleDash()
    {
        $input = new ArgvInput(['cli.php', '--', '-1']);
        $input->bind(new InputDefinition([new InputArgument('number')]));
        $this->assertEquals(['number' => '-1'], $input->getArguments(), '->parse() parses arguments with leading dashes as arguments after having encountered a double-dash sequence');

        $input = new ArgvInput(['cli.php', '-f', 'bar', '--', '-1']);
        $input->bind(new Inp