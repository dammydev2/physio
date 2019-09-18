ce();
        $bar->finish();

        rewind($output->getStream());
        $this->assertEquals(
            rtrim('    0 [>---------------------------]').
            rtrim($this->generateOutput('    1 [->--------------------------]')).
            rtrim($this->generateOutput('    2 [-->-------------------------]')).
            rtrim($this->generateOutput('    3 [--->------------------------]')).
            rtrim($this->generateOutput('    3 [============================]')),
            stream_get_contents($output->getStream())
        );
    }

    public function testSettingMaxStepsDuringProgressing()
    {
        $output = $this->getOutputStream();
        $bar = new ProgressBar($output);
        $bar->start();
        $bar->setProgress(2);
        $bar->setMaxSteps(10);
        $bar->setProgress(5);
        $bar->setMaxSteps(100);
        $bar->setProgress(10);
        $bar->finish();

        rewind($output->getStream());
        $this->assertEquals(
            rtrim('    0 [>---------------------------]').
            rtrim($this->generateOutput('    2 [-->-------------------------]')).
            rtrim($this->generateOutput('  5/10 [==============>-------------]  50%')).
            rtrim($this->generateOutput('  10/100 [==>-------------------------]  10%')).
            rtrim($this->generateOutput(' 100/100 [============================] 100%')),
            stream_get_contents($output->getStream())
        );
    }

    public function testWithSmallScreen()
    {
        $output = $this->getOutputStream();

        $bar = new ProgressBar($output);
        putenv('COLUMNS=12');
        $bar->start();
        $bar->advance();
        putenv('COLUMNS=120');

        rewind($output->getStream());
        $this->assertEquals(
            '    0 [>---]'.
            $this->generateOutput('    1 [->--]'),
            stream_get_contents($output->getStream())
        );
    }

    public function testAddingPlaceholderFormatter()
    {
        ProgressBar::setPlaceholderFormatterDefinition('remaining_steps', function (ProgressBar $bar) {
            return $bar->getMaxSteps() - $bar->getProgress();
        });
        $bar = new ProgressBar($output = $this->getOutputStream(), 3);
        $bar->setFormat(' %remaining_steps% [%bar%]');

        $bar->start();
        $bar->advance();
        $bar->finish();

        rewind($output->getStream());
        $this->assertEquals(
            ' 3 [>---------------------------]'.
            $this->generateOutput(' 2 [=========>------------------]').
            $this->generateOutput(' 0 [============================]'),
            stream_get_contents($output->getStream())
        );
    }

    public function testMultilineFormat()
    {
        $bar = new ProgressBar($output = $this->getOutputStream(), 3);
        $bar->setFormat("%bar%\nfoobar");

        $bar->start();
        $bar->advance();
        $bar->clear();
        $bar->finish();

        rewind($output->getStream());
        $this->assertEquals(
            ">---------------------------\nfoobar".
            $this->generateOutput("=========>------------------\nfoobar").
            "\x0D\x1B[2K\x1B[1A\x1B[2K".
            $this->generateOutput("============================\nfoobar"),
            stream_get_contents($output->getStream())
        );
    }

    public function testAnsiColorsAndEmojis()
    {
        putenv('COLUMNS=156');

        $bar = new ProgressBar($output = $this->getOutputStream(), 15);
        ProgressBar::setPlaceholderFormatterDefinition('memory', function (ProgressBar $bar) {
            static $i = 0;
            $mem = 100000 * $i;
            $colors = $i++ ? '41;37' : '44;37';

            return "\033[".$colors.'m '.Helper::formatMemory($mem)." \033[0m";
        });
        $bar->setFormat(" \033[44;37m %title:-37s% \033[0m\n %current%/%max% %bar% %percent:3s%%\n 🏁  %remaining:-10s% %memory:37s%");
        $bar->setBarCharacter($done = "\033[32m●\033[0m");
        $bar->setEmptyBarCharacter($empty = "\033[31m●\033[0m");
        $bar->setProgressCharacter($progress = "\033[32m➤ \033[0m");

        $bar->setMessage('Starting the demo... fingers crossed', 'title');
        $bar->start();

     