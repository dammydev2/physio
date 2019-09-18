<?php

namespace Symfony\Component\Console\Tests\Helper;

use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * @group tty
 */
class SymfonyQuestionHelperTest extends AbstractQuestionHelperTest
{
    public function testAskChoice()
    {
        $questionHelper = new SymfonyQuestionHelper();

        $helperSet = new HelperSet([new FormatterHelper()]);
        $questionHelper->setHelperSet($helperSet);

        $heroes = ['Superman', 'Batman', 'Spiderman'];

        $inputStream = $this-