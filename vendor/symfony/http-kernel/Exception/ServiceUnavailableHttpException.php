)->getFileName();
        unlink(__DIR__.'/Fixtures/var/cache/custom/TestsSymfony_Component_HttpKernel_Tests_CustomProjectDirKernelCustomDebugContainer.php.meta');

        $kernel = new CustomProjectDirKernel();
        $kernel->boot();

        $this->assertInstanceOf($containerClass, $kernel->getContainer());
        $this->assertFileExists($containerFile);
        unlink(__DIR__.'/Fixtures/var/cache/custom/TestsSymfony_Component_HttpKernel_Tests_CustomProjectDirKernelCustomDebugContainer.php.meta');

        $kernel = new CustomProjectDirKernel(function ($container) { $container->register('foo', 'stdClass')->setPublic(true); });
        $kernel->boot();

        $this->assertNotInstanceOf($containerClass, $kernel->getContainer());
        $this->assertFileExists($containerFile);
        $this->assertFileExists(\dirname($containerFile).'.legacy');
    }

    public function testKernelPass()
    {
        $kernel = new PassKernel();
        $kernel->boot();

        $this->assertTrue($kernel->getContainer()->getParameter('test.processed'));
    }

    pu