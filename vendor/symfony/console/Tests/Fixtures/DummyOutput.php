ent 2', 'My environment 2'],
            ['My environment 3', 'My environment 3'],
        ];
    }

    /**
     * @dataProvider specialCharacterInMultipleChoice
     */
    public function testSpecialCharacterChoiceFromMultipleChoiceList($providedAnswer, $expectedValue)
    {
        $possibleChoices = [
            '.',
            'src',
        ];

        $dialog = new QuestionHelper();
        $inputStream = $this->getInputStream($providedAnswer."\n");
        $helperSet = new HelperSet([new FormatterHelper()]);
        $dialog->setHelperSet($helperSet);

        $question = new ChoiceQuestion('Please select the directory', $possibleChoices);
        $question->setMaxAttempts(1);
        $question->setMultiselect(true);
        $answer = $dialog->ask($this->createStreamableInputI