ernel
            ->expects($this->exactly(2))
            ->method('getBundle')
            ->will($this->returnValue($this->getBundle(__DIR__.'/Fixtures/FooBundle', null, null, 'FooBundle')))
        ;

        $this->assertEquals(
            __DIR__.'/Fixtures/Resources/FooBundle/',
            $kernel->locateResource('@FooBundle/Resources/', __DIR__.'/Fixtures/Resources')
        );
        $this->assertEquals(
            __DIR__.'/Fixtures/Resources/FooBundle',
            $kernel->locateResource('@FooBundle/Resources', __DIR__.'/Fixtures/Resources')
        );

        $kernel = $this->getKernel(['getBundle']);
        $kernel
            ->expects($this->exactly(2))
            ->method('getBundle')
            ->will($this->returnValue($this->getBundle(__DIR__.'/Fixtures/Bundle1Bundle', null, null, 'Bundle1Bundle')))
        ;

        $this->assertEquals(
            __