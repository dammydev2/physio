e()
           ->with($message, $failures)
           ->andReturnUsing(function () use ($connectionState) {
               if ($connectionState) {
                   return 1;
               }
           });

        $transport = $this->getTransport([$t1]);
        $transport->start();
        $transport->send($message, $failures);
    }

    public function testRegisterPluginDelegatesToLoadedTransports()
    {
        $plugin = $this->createPlugin();

        $t1 = $this->getMockery('Swift_Transport');
        $t2 = $this->getMockery('Swift_Transport');
        $t1->shouldReceive('registerPlugin')
           ->once()
           ->with($plugin);
        $t2->shouldReceive('registerPlugin')
           ->once()
           ->with($plugin);

        $transport = $this->getTransport([$t1, $t2]);
        $transport->registerPlugin($plugin);
    }

    public function testEachDelegateIsPinged()
    {
        $t1 = $this->getMockery('Swift_Transport');
        $t2 = $this->getMockery('Swift_Transport');
        $connectionState1 = false;
        $connectionState2 = false;

        $testCase = $this;
        $t1->shouldReceive('isStarted')
           ->zeroOrMoreTimes()
           ->andReturnUsing(function () use (&$connectionState1) {
               return $connectionState1;
           });
        $t1->shouldReceive('ping')
           ->once()
           ->andReturn(true);

        $transport = $this->getTransport([$t1, $t2]);
        $this->assertTrue($transport->isStarted());
        $this->assertTrue($transport->ping());
    }

    public function testDelegateIsKilledWhenPingFails()
    {
        $t1 = $this->getMockery('Swift_Transport');
        $t2 = $this->getMockery('Swift_Transport');

        $testCase = $this;
        $t1->shouldReceive('isStarted')
           ->zeroOrMoreTimes()
           ->andReturnUsing(function () use (&$connectionState1) {
               return $connectionState1;
           });
        $t1->shouldReceive('ping')
           ->once()
           ->andReturn(false);

        $t2->shouldReceive('isStarted')
           ->zeroOrMoreTimes()
           ->andReturnUsing(function () use (&$connectionState2) {
               return $connectionState2;
           });
        $t2->shouldReceive('ping')
           ->twice()
           ->andReturn(true);

        $transport = $this->getTransport([$t1, $t2]);
        $this->assertTrue($transport->ping());
        $this->assertTrue($transport->ping());
        $this->assertTrue($transport->isStarted());
    }

    public function XtestTransportShowsAsNotStartedIfAllPingFails()
    {
        $t1 = $this->getMockery('Swift_Transport');
        $t2 = $this->getMockery('Swift_Transport');

        $testCase = $this;
        $t1->shouldReceive('isStarted')
           ->zeroOrMoreTimes()
           ->andReturnUsing(function () use (&$connectionState1) {
               ret