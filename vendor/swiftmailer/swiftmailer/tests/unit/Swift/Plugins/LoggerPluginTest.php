andReturnUsing(function () use (&$connectionState1) {
               return $connectionState1;
           });
        $t1->shouldReceive('start')
           ->twice()
           ->andReturnUsing(function () use (&$connectionState1) {
               if (!$connectionState1) {
                   $connectionState1 = true;
               }
           });
        $t1->shouldReceive('send')
           ->once()
           ->with($message1, \Mockery::any())
           ->andReturnUsing(function () use (&$connectionState1, $e) {
               if ($connectionState1) {
                   $connectionState1 = false;
                   throw $e;
               }
           });
        $t1->shouldReceive('send')
           ->once()
           ->with($message2, \Mockery::any())
           ->andReturnUsing(function () use (&$connectionState1, $e) {
               if ($connectionState1) {
                   return 10;
               }
           });

        $t2->shouldReceive('isStarted')
           ->zeroOrMoreTimes()
           ->andReturnUsing(function () use (&$connectionState2) {
               return $connectionState2;
           });
        $t2->shouldReceive('start')
           ->once()
           ->andReturnUsing(function () use (&$connectionState2) {
               if (!$connectionState2) {
                   $connectionState2 = true;
               }
           });
        $t2->shouldReceive('send')
           ->once()
           ->with($message1, \Mockery::any())
           ->andReturnUsing(function () use (&$connectionState2, $e) {
               if ($connectionState2) {
                   throw $e;
               }
           });
        $t2->shouldReceive('send')
           ->never()
           ->with($message2, \Mockery::any());

        $transport = $this->getTransport([$t1, $t2]);
        $transport->start();
        $this->assertTrue($transport->isStarted());
        try {
            $transport->send($message1);
            $this->fail('All transports failed so Exception should be thrown');
        } catch (Exception $e) {
            $this->assertFalse($transport->isStarted());
        }
        //Restart and re-try
        $transport->start();
        $this->assertTrue($transport->isStarted());
        $this->assertEquals(10, $transport->send($message2));
    }

    public function testFailureReferenceIsPassedToDelegates()
    {
        $failures = [];
        $testCase = $this;

        $message = $this->getMockery('Swift_Mime_SimpleMessage');
        $t1 = $this->getMockery('Swift_Transport');
        $connectionState = false;

        $t1->shouldReceive('isStarted')
           ->zeroOrMoreTimes()
           ->andReturnUsing(function () use (&$connectionState) {
               return $connectionState;
           });
        $t1->shouldReceive('start')
           ->once()
           ->andReturnUsing(function () use (&$connectionState) {
               if (!$connectionState) {
                   $connectionState = true;
               }
           });
        $t1->shouldReceive('send')
           ->once()
           ->with($message, \Mockery::on(function (&$var) use (&$failures, $testCase) {
               return $testCase->varsAreReferences($var, $failures);
           }))
           ->andReturnUsing(function () use (&$connectionState) {
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

        $testCase = $this;
        $t1->shouldReceive('isStarted')
           ->zeroOrMoreTimes()
           ->andReturnUsing(function () use (&$connectionState1) {
               return $connectionState1;
           });
        $t1->shouldReceive('ping')
           ->once()
           ->andReturn(true);

        $t2->shouldReceive('isStarted')
           ->zeroOrMoreTimes()
           ->andReturnUsing(function () use (&$connectionState2) {
               return $connectionState2;
           });
        $t2->shouldReceive('ping')
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
           ->twice()
           ->andReturn(true);

        $t2->shouldReceive('isStarted')
           ->zeroOrMoreTimes()
           ->andReturnUsing(function () use (&$connectionState2) {
               return $connectionState2;
           });
  