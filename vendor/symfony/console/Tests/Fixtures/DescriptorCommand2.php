ndle']);
        $question->setMaxAttempts(1);

        $this->assertEquals('AcmeDemoBundle', $dialog->ask($this->createStreamableInputInterfaceMock($inputStream), $this->createOutputInterface(), $question));
        $this->assertEquals('AsseticBundle', $dialog->ask($this->createStreamableInputInterfaceMock($inputStream), $this->createOutputInterface(), $question));
    }

    public function testAskWithAutocompleteWithExactMatch()
    {
        if (!$this->hasSttyAvailable()) {
            $this->markTestSkipped('`stty` is required to test autocomplete functionality');
        }

        $inputStream = $this->getInputStream("b\n");

        $possibleChoices = [
            'a' => 'berlin',
            'b' => 'copenhagen',
            'c' => 'amsterdam',
        ];

        $dialog = new QuestionHelper();
        $dialog->setHelperSet(new HelperSet([new FormatterHelper()]));

        $question = new ChoiceQuestion('Ple