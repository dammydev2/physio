sts($this, 'expectException')) {
            $this->expectException('RuntimeException');
            $this->expectExceptionMessage($expectedExceptionMessage);
        } else {
            $this->setExpectedException('RuntimeException', $expectedExceptionMessage);
        }

        $input = new ArgvInput($argv);
        $input->bind($definition);
    }

    public function provideInvalidInput()
    {
        return [
            [
                ['cli.php', '--foo'],
                new InputDefinition([new InputOption('foo', 'f', InputOption::VALUE_REQUIRED)]),
                'The "--foo" option requires a value.',
            ],
            [
                ['cli.php', '-f'],
                new InputDefinition([new InputOption('foo', 'f', InputOption::VALUE_REQUIRED)]),
                'The "--foo" option requires a value.',
            ],
            [
                ['cli.php', '-ffoo'],
                new InputDefinition([new InputOption('foo', 'f', InputOption::VALUE_NONE)]),
                'The "-o" option does not exist.',
            ],
            [
                ['cli.php', '--foo=bar'],
                new InputDefinition([new InputOption('foo', 'f', InputOption::VALUE_NONE)]