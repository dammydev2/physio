this->dispatcher->hasListeners('foo'));
        $this->assertSame(0, $called);
    }

    public function testDispatchLazyListener()
    {
        $called = 0;
        $factory = function () use (&$called) {
            ++$called;

            return new TestWithDispatcher();
        };
        $this->dispatcher->addListener('foo', [$factory, 'foo']);
        $this->assertSame(0, $called);
        $this->dispatcher->dispatch('foo', new Event());
        $this->dispatcher->dispatch('foo', new Event());
        $this->assertSame(1, $called);
    }

    public function testRemoveFindsLazyListeners()
    {
        $test = new TestWithDispatcher();
        $factory = function () use ($test) { return $test; };

        $this->dispatcher->addListener('foo', [$factory, 'foo']);
        $this->assertTrue($this->dispatcher->hasListeners('foo'));
        $this->disp