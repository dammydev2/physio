', 'initializeContainer', 'getBundles']);
        $kernel->expects($this->once())
            ->method('getBundles')
            ->will($this->returnValue([$bundle]));

        $kernel->boot();
    }

    public function testBootSetsTheBootedFlagToTrue()
    {
        // use test kernel to access isBooted()
        $kernel = $this->getKernelForTest(['initializeBundles', 'initializeContainer']);
        $kernel->boot();

        $this->assertTrue($kernel->isBooted());
    }

    public function testClassCacheIsNotLoadedByDefault()
    {
        $kernel = $this->getKernel(['initializeBundles', 'initializeContainer', 'doLoadClassCache']);
        $kernel->expects($this->never())
            ->method('doLoadClassCache');

        $kernel->boot();
    }

    public function testBootKernelSeveralTimesOnlyInitializesBundlesOnce()
    {
 