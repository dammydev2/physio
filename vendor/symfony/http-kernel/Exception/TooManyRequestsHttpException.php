= 'registerBundles';

        $kernel = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\Kernel')
            ->setMethods($methods)
            ->setConstructorArgs(['test', false])
            ->getMockForAbstractClass()
        ;
        $kernel->expects($this->any())
            ->method('registerBundles')
            ->will($this->returnValue($bundles))
        ;
        $p = new \ReflectionProperty($kernel, 'rootDir');
        $p->setAccessible(true);
        $p->setValue($kernel, __DIR__.'/Fixtures');

        return $kernel;
    }

    protected function getKernelForTest(array $methods = [], $debug = false)
    {
        $kernel = $this->getMockBuilder('Symfony\Component\HttpKernel\Tests\Fixtures\KernelForTest')
            ->setConstructorArgs(['test', $debug])
            ->setMethods($methods)
            ->getMock();
        $p = new \ReflectionProperty($kernel, 'rootDir');
        $p->setAccessible(true);
        $p->setValue($kernel, __DIR__.'/Fixtures');

        return $kernel;
    }
}

class TestKernel implements HttpKernelInterface
{
    public $terminateCalled = f