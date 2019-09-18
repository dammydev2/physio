            usleep(10000);
        }

        $this->assertFalse($process->isRunning());
        $this->assertTrue($process->hasBeenSignaled());
        $this->assertFalse($process->isSuccessful());
        $this->assertEquals(137, $process->getExitCode());
    }

    /**
     * @expectedException \Symfony\Component\Process\Exception\LogicException
     * @expectedExceptionMessage Can not send signal on a non running process.
     */
    public function testSignalProcessNotRunning()
    {
        $process = $this->getProcess('foo');
        $process->signal(1); // SIGHUP
    }

    /**
     * @dataProvider provideMethodsThatNeedARunningProcess
     */
    public function testMethodsThatNeedARunningProcess($method)
    {
        $process = $this->getProcess('foo');

        if (method_exists($this, 'expectException')) {
            $this->expectException('Symfony\Component\Process\Exception\LogicException');
            $this->expectExceptionMessage(sprintf('Process must be started before calling %s.', $method));
        } else {
            $this->setExpectedException('Symfony\Component\Process\Exception\LogicException', sprintf('Process must be started before calling %s.', $method));
        }

        $process->{$method}();
    }

    public function provideMethodsThatNeedARunningProcess()
    {
        return [
            ['getOutput'],
            ['getIncrementalOutput'],
            ['getErrorOutput'],
      