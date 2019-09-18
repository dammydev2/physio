cher();
        $dispatcher->addListener(KernelEvents::EXCEPTION, function ($event) {
            $event->setResponse(new Response($event->getException()->getMessage()));
        });

        $kernel = $this->getHttpKernel($dispatcher, function () { throw new MethodNotAllowedHttpException(['POST']); });
        $response = $kernel->handle(new Request());

        $this->assertEquals('405', $response->getStatusCode());
        $this->assertEquals('POST', $response->headers->get('Allow'));
    }

    public function getStatusCodes()
    {
        return [
            [200, 404],
            [404, 200],
            [301, 200],
            [500, 200],
        ];
    }

    /**
     * @d