 $start);
    }

    /**
     * @dataProvider getHostValidities
     */
    public function testHostValidity($host, $isValid, $expectedHost = null, $expectedPort = null)
    {
        $request = Request::create('/');
        $request->headers->set('host', $host);

        if ($isValid) {
            $this->assertSame($expectedHost ?: $host, $request->getHost());
            if ($expectedPort) {
                $this->assertSame($expectedPort, $request->getPort());
            }
        } else {
            if (method_exists($this, 'expectException')) {
                $this->expectException(SuspiciousOperationException::class);
                $this->expectExceptionMessage('Invalid Host');
            } else {
                $this->setExpectedException(SuspiciousOperationException::class, 'Invalid Host');
            }

            $request->getHost();
        }
    }

    public function getHostValidities()
    {
        return [
            ['.a', false],
            ['a..', false],
            ['a.', true],
            ["\xE9", false],
            ['[::1]', true],
            ['[::1]:80', true, '[::1]', 80],
            [str_repeat('.', 10