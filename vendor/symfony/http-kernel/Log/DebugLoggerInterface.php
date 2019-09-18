function ($v) {
            if (isset($v['context']['exception'])) {
                $e = &$v['context']['exception'];
                $e = isset($e["\0*\0message"]) ? [$e["\0*\0message"], $e["\0*\0severity"]] : [$e["\0Symfony\Component\Debug\Exception\SilencedErrorContext\0severity"]];
            }

            return $v;
        }, $c->getLogs()->getValue(true));
        $this->assertEquals($expectedLogs, $logs);
        $this->assertEquals($expectedDeprecationCount, $c->countDeprecations());
        $this->assertEquals($expectedScreamCount, $c->countScreams());

        if (isset($expectedPriorities)) {
            $this->assertSame($expectedPriorities, $c->getPriorities()->getValue(true));
        }
    }

    public function testReset()
    {
        $logger = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\Log\DebugLoggerInterface')
            ->setMethods(['countErrors', 'getLogs', 'clear'])
            ->getMock();
        $logger->expects($this->once())->method('clear');

        $c = new LoggerDataCollector($logger);
        $c->reset();
    }

    public function getCollectTestData()
    {
        yield 'simple log' => [
            1