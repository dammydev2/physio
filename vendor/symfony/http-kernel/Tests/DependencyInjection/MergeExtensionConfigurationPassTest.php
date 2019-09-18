rnel($this->onConsecutiveCalls(
            $this->throwException(new \RuntimeException('foo')),
            $this->returnValue(new Response('bar'))
        )));

        $this->assertEquals('bar', $strategy->render('/', Request::create('/'), ['ignore_errors' => true, 'alt' => '/foo'])->getContent());
    }

    private function getKernel($returnValue)
    {
        $kernel = $this->getMockBuilder('Symfony\Component\HttpKernel\HttpKernelInterface')->getMock();
        $kernel
            ->expects($this->any())
            ->method('handle')
            ->will($returnValue)
        ;

        return $kernel;
    }

    public function testExceptionInSubRequestsDoesNotMangleOutputBuffers()
    {
        $controllerResolver = $this->getMockBuilder('Symfony\\Component\\HttpKernel\\Controller\\ControllerResolverInterface')->getMock();
        $controllerResolver
            ->expects($this->once())
            ->method('getController')
            ->will($this->returnValue(function () {
                ob_start();
                echo 'bar';
                throw new \RuntimeException();
            }))
        ;

        $argumentResolver = $this->getMockBuilder('Symfony\\Component\\HttpKernel\\Controller\\ArgumentResolverInterface')->getMock();
        $argumentResolver
            ->expects($this->once())
            ->method('getArguments')
            ->will($this->returnValue([]))
        ;

        $kernel = new HttpKernel(new EventDispatcher(), $controllerResolver, new RequestStack(), 