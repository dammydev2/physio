mableInputInterfaceMock($inputStream), $output = $this->createOutputInterface(), $question));
        $this->assertOutputContains('What is your favorite superhero? [Superman, Batman]', $output);
    }

    public function testAskChoiceWithChoiceValueAsDefault()
    {
        $questionHelper = new SymfonyQuestionHelper();
        $helperSet = new HelperSet([new FormatterHelper()]);
        $questionHelper->setHelperSet($helperSet);
        $question = new ChoiceQuestion('What is your favorite superhero?', ['Superman', 'Batman', 'Spiderman'], 'Batman');
        $question->setMaxAttempts(1);

        $this->assertSame('Batman', $questionHelper->ask($this->createStreamableInputInterfaceMock($this->getInputStream("Batman\n")), $output = $this->createOutputInterface(), $question));
        $this->assertOutputContains('What is your favorite superhero? [Batman]', $output);
    }

    public function testAskReturnsNullIfValidatorAllowsIt()
    {
        $questio