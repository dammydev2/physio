est());

            $this->fail('The kernel should throw an exception.');
        } catch (ControllerDoesNotReturnResponseException $e) {
        }

        $first = $e->getTrace()[0];

        // `file` index the array starting at 0, and __FILE__ starts at 1
        $line = file($first['file'])[$first['line'] - 2];
        $this->assertContains('// call controller', $line);
    }

    public function testHandleWhenTheControllerDoesNotReturnAResponseButAViewIsRegistered()
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(KernelEvents::VIEW, function ($event) {
            $event->setResponse(new Response($event->getControllerResult()));
        });

        $kernel = $this->getHttpKernel($dispatcher, function () { return 'foo'; });

        $this->assertEquals('foo', $kernel->handle(new Request(