ttpKernel\Tests\Controller\ControllerTestService" cannot be fetched from the container because it is private. Did you forget to tag the service with "controller.service_arguments"?
     */
    public function testExceptionWhenUsingRemovedControllerServiceWithClassNameAsName()
    {
        $container = $this->getMockBuilder(Container::class)->getMock();
        $container->expects($this->once())
            ->method('has')
            ->with(ControllerTestService::class)
            ->will($this->returnValue(false))
        ;

        $container->expects($this->atLeastOnce())
            ->method('getRemovedIds')
            ->with()
            ->will($this->returnValue([ControllerTestService::class => true]))
        ;

        $resolver = $this->createControllerResolver(null, $container);
        $request = Request::create('/');
        $request->attributes->set('_controller', [ControllerTestService::class, 'action']);

        $resolver->getController($request);
    }

    /**
     * Tests where the fallback instantiation fails due to non-existing class.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Controller "app.my_controller" cannot be fetched from the container because it is private. Did you forget to tag the service with "controller.service_arguments"?
     */
    public function testExceptionWhenUsingRemovedControllerService()
    {
        $container = $this->getMockBuilder(Container::class)->getMock();
        $container->expects($this->once())
            ->method('has')
            ->with('app.my_controller')
            ->will($this->returnValue(false))
        ;

        $container->expects($this->atLeastOnce())
            ->method('getRemovedIds')
            ->with()
            ->will($this->returnValue(['app.my_controller' => true]))
        ;

        $resolver = $this->createControllerResolver(null, $container);

        $request = Request::create('/');
        $request->attributes->set('_controller', 'app.my_controller');
        $resolver->getController($request);
    }

    public function getUndefinedControllers()
    {
        $tests = parent::getUndefinedControllers();
        $tests[0] = ['foo', \InvalidArgumentException::class, 'Controller "foo" does neither exist as service nor as class'];
        $tests[1] = ['oof::bar', \InvalidArgumentException::class, 'Controller "oof" does neither exist as service nor as class'];
        $tests[2] = [['oof', 'bar'], \InvalidArgumentException::class, 'Controller "oof" does neither exist as service nor as class'];
        $tests[] = [
            [ControllerTestService::class, 'action'],
            \InvalidArgumentException::class,
            'Controller "Symfony\Component\HttpKernel\Tests\Controller\ControllerTestService" has required constructor arguments and does not exist in the container. Did you forget to define such a service?',
        ];
        $tests[] = [
            ControllerTestService::class.'::action',
            \InvalidArgumentException::class, 'Controller "Symfony\Component\HttpKernel\Tests\Controller\ControllerTestService" has required constructor arguments and does not exist in the container. Did you forget to define such a service?',
        ];
        $tests[] = [
            InvokableControllerService::class,
            \InvalidArgumentException::class,
            'Controller "Symfony\Component\HttpKernel\Tests\Controller\InvokableControllerService" has required constructor arguments and does not exist in the container. Did you forget to define such a service?',
        ];

        return $tests;
    }

    protected function createControllerResol