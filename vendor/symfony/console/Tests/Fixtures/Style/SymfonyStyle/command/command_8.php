arameterOption() returns true if the given option with provided value is in the raw input');
    }

    public function testHasParameterOptionOnlyOptions()
    {
        $input = new ArgvInput(['cli.php', '-f', 'foo']);
        $this->assertTrue($input->hasParameterOption('-f', true), '->hasParameterOption() returns true if the given short option is in the raw input');

        $input = new ArgvInput(['cli.php', '--foo', '--', 'foo']);
        $this->assertTrue($input->hasParameterOption('--foo', true), '->hasParameterOption() returns true if the given long option is in the raw input');

        $input = new ArgvInput(['cli.php', '--foo=bar', 'foo']);
        $this->assertTrue($input->hasParameterOption('--foo', true), '->hasParameterOption() returns true if the given long option wit