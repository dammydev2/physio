tion \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testInconsistentClientIpsOnMasterRequests()
    {
        $request = new Request();
        $request->setTrustedProxies(['1.1.1.1'], Request::HEADER_X_FORWARDED_FOR | Request::HEADER_FORWARDED);
        $request->server->set('REMOTE_ADDR', '1.1.1.1');
        $request->headers->set('FORWARDED', 'for=2.2.2.2');
        $request->headers->set('X_FORWARDED_FOR', '3.3.3.3');

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(KernelEvents::REQUEST, function ($event) {
            $event->getRequest()->getClientIp();
        });

        $kernel = $this->getHttpKernel($dispatcher);
        $kernel->handle($request, $kernel::MASTER_REQUEST, false);

        Request::setTrustedProxies([], -1);
    }

    private function getHttpKernel(EventDispatcherInterface $eventDispatcher, $controller = null, RequestStack $requestStack = null, array $arguments = [])
    {