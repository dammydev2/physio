->dispatcher->getListenerPriority('pre.foo', $listener1));
        $this->assertSame(0, $this->dispatcher->getListenerPriority('pre.foo', $listener2));
        $this->assertNull($this->dispatcher->getListenerPriority('pre.bar', $listener2));
        $this->assertNull($this->dispatcher->getListenerPriority('pre.foo', function () {}));
    }

    public function testDispatch()
    {
        $this->dispatcher->addListener('pre.foo', [$this->listener, 'preFoo']);
        $this->dispatcher->addListener('post.foo', [$this->listener, 'postFoo']);
        $this->dispatcher->dispatch(self::preFoo);
        $this->assertTrue($this->listener->preFooInvoked);
        $this->assertFalse($this->listener->postFooInvoked);
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\Event', $this->dispatcher->dispatch('noevent'));
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\Event', $this->dispatcher->dispatch(self::preFoo));
        $event = new Event();
        $return = $this->dispatcher->dispatch(self::preFoo, $event);
        $this->assertSame($event, $return);
    }

    public function testDispatchForClosure()
    {
        $invoked = 0;
        $listener = function () use (&$invoked) {
            ++$invoked;
        };
        $this->dispatcher->addListener('pre.foo', $listener);
        $this->dispatcher->addListener('post.foo', $listener);
        