nction testResponseIsStillPublicIfSessionStartedAndHeaderPresent()
    {
        $session = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
        $session->expects($this->exactly(2))->method('getUsageIndex')->will($this->onConsecutiveCalls(0, 1));

        $container = new Container();
        $container->set('initialized_session', $session);

        $listener = new SessionListener($container);
        $kernel = $this->getMockBuilder(HttpKernelInterface::class)->disableOriginalConstructor()->getMock();

        $request = new Request();
        $listener->onKernelRequest(new GetResponseEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST));

        $response = new Response();
        $response->setSharedMaxAge(60);
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true');
        $listener->onKernelResponse(new FilterResponseEvent($kernel, new Request(), HttpKernelInterface::MASTER_REQUEST, $response));

        $this->assertTrue($response->headers->hasCacheControlDirective('public'));
        $this->assertFalse($response->headers->hasCacheControlDirective('private'));
        $this->assertFalse($response->headers->hasCacheControlDirective('must-revalidate'));
        $this->assertSame('60', $response->headers->getCacheControlDirective('s-maxage'));
    