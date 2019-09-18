ell->getIncludes();
        $this->assertSame('/file.php', $includes[0]);
    }

    public function testAddMatchersViaConfig()
    {
        $shell = new FakeShell();
        $matcher = new ClassMethodsMatcher();

        $config = $this->getConfig([
            'matchers' => [$matcher],
        ]);
        $config->setShell($shell);

        $this->assertSame([$matcher], $shell->matchers);
    }

    public function testAddMatchersViaConfigAfterShell()
    {
        $shell = new FakeShell();
        $matcher = new ClassMethodsMatcher();

        $config = $this->getConfig([]);
        $config->setShell($shell);
        $config->addMatchers([$matcher]);

        $this->assertSame([$matcher], $shell->matchers);
    }

    public function testRenderingExceptions()
    {
        $shell  = new Shell($this->getConfig());
        $output = $this->getOutput();
        $stream = $output->getStream();
        $e      = new ParseErrorException('message', 13);

        $shell->setOutput($output);
        $shell->addCode('code');
        $this->assertTrue($shell->hasCode());
        $this->assertNotEmpty($shell->getCodeBuffer());

        $shell->writeException($e);

        $this->assertSame($e, $shell->getScopeVariable('_e'));
        $this->assertFalse($shell->hasCode());
        $this->assertEmpty($shell->getCodeBuffer());

        \rewind($stream);
        $streamContents = \stream_get_contents($stream);

        $this->assertContains('PHP Parse error', $streamContents);
        $this->assertContains('message', $streamContents);
        $this->assertContains('line 13', $streamContents);
    }

    public function testHandlingErrors()
    {
        $shell  = new Shell($this->getConfig());
        $output = $this->getOutput();
        $stream = $output->getStream();
        $shell->setOutput($output);

        $oldLevel = \error_reporting();
        \error_reporting($oldLevel & ~E_USER_NOTICE);

        try {
            $shell->handleError(E_USER_NOTICE, 'wheee', null, 13);
        } catch (ErrorException $e) {
            \error_reporting($oldLevel);
            $this->fail('Unexpected error exception');
        }
        \error_reporting($oldLevel);

        \rewind($stream);
        $streamContents = \stream_get_contents($stream);

        $this->assertContains('PHP Notice:', $streamContents);
        $this->assertContains('wheee',       $streamContents);
        $this->assertContains('line 13',     $streamContents);
    }

    /**
     * @expectedException \Psy\Exception\ErrorException
     */
    public function testNotHandlingErrors()
    {
        $shell    = new Shell($this->getConfig());
        $oldLevel = \error_reporting();
        \error_reporting($oldLevel | E_USER_NOTICE);

        try {
            $shell->handleError(E_USER_NOTICE, 'wheee', null