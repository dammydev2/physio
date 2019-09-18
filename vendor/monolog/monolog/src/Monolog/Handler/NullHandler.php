/
    public function testRegisterDefaultPreviousSignalHandler($signo, $callPrevious, $expected)
    {
        $this->setSignalHandler($signo, SIG_DFL);

        $path = tempnam(sys_get_temp_dir(), 'monolog-');
        $this->assertNotFalse($path);

        $pid = pcntl_fork();
        if ($pid === 0) {  // Child.
            $streamHandler = new StreamHandler($path);
            $streamHandler->setFormatter($this->getIdentityFormatter());
            $logger = new Logger('test', array($streamHandler));
            $errHandler = new SignalHandler($logger);
            $errHandler->registerSignalHandler($signo, LogLevel::INFO, $callPrevious, false, false);
            pcntl_sigprocmask(SIG_SETMASK, array(SIGCONT));
            posix_kill(posix_getpid(), $signo);
            pcntl_signal_dispatch();
            // If $callPrevious is true, SIGINT should terminate by this line.
            pcntl_sigprocmask(SIG_BLOCK, array(), $oldset);
     