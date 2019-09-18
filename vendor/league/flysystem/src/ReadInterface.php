.. index::
    single: Cookbook; Mocking Hard Dependencies

Mocking Hard Dependencies (new Keyword)
=======================================

One prerequisite to mock hard dependencies is that the code we are trying to test uses autoloading.

Let's take the following code for an example:

.. code-block:: php

    <?php
    namespace App;
    class Service
    {
        function callExternalService($param)
        {
            $externalService = new Service\External();
            $externalService->sendSomething($param);
            return $externalService->getSomething();
        }
    }

The way we can test this without doing any changes to the code itself is by creating :doc:`instance mocks </reference/instance_mocking>` by using the ``overload`` prefix.

.. code-block:: php

    <?php
    namespace AppTest;
    use Mockery as m;
    class ServiceTest extends \PHPUnit_Framework_TestCase
    {
        public function testCallingExternalService()
        {
            $param = 'Testing';

            $externalMock = m::mock('overload:App\Service\External');
            $externalMock->shouldReceive('sendSomething')
                ->once()
                ->with($param);
            $externalMock->shouldReceive('getSomething')
                ->once()
                ->andReturn('Tested!');

            $service = new \App\Service();

            $result = $service->callExternalService($param);

            $this->assertSame('Tested!', $result);
        }
    }

If we run this test now, it should pass. Mockery does its job and our ``App\Service`` will u