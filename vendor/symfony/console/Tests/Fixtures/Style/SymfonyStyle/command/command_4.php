IONAL)],
                ['foo' => null],
                '->parse() parses long options with optional value specified with no separator and no value as null',
            ],
            [
                ['cli.php', '-f'],
                [new InputOption('foo', 'f')],
                ['foo' => true],
                '->parse() parses short options without a value',
            ],
            [
                ['cli.php', '-fbar'],
                [new InputOption('foo', 'f', InputOption::VALUE_REQUIRED)],
                ['foo' => 'bar'],
                '->parse() parses short options with a required value (with no separator)',
            ],
            [
                ['cli.php', '-f', 'bar'],
                [new InputOption('foo', 'f', InputOption::VALUE_REQUIRED)],
                ['foo' => 'bar'],
                '->parse() parses short options with a required value (with a space separator)',
            ],
            [
                ['cli.php', '-f', ''],
                [new InputOption('foo', 'f', InputOption::VALUE_OPTIONAL)],
                ['foo' => ''],
                '->parse() parses short options with an optional empty value',
    