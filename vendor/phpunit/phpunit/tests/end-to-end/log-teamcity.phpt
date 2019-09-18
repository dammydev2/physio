--TEST--
\PHPUnit\Framework\MockObject\Generator::generate('Foo', [], 'MockFoo', true, true)
--FILE--
<?php
interface Foo
{
    public function bar(): Closure;
}

require __DIR__ . '/../../../../vendor/autoload.php';

$generator = new \PHPUnit\Framework\MockObject\Generator;

$mock = $generator->generate(
    'Foo',
    [],
    'MockFoo',
    true,
    true
);

print $mock['code'];
?>
--EXPECT--
class MockFoo implements PHPUnit\Framework\MockObject\MockObject, Foo
{
    private $__phpunit_invocationMocker;
    private $__phpunit_originalObject;
    private $__phpunit_configurable = ['bar'];
    private $__phpunit_returnValueGeneration = true;

    public function __clone()
    {
        $this->__phpunit_invocationMocker = clone $this->__phpunit_getInvocationMocker();
    }

    public function bar(): Closure
    {
        $__phpunit_arguments = [];
        $__phpunit_count     = func_num_args();

        if ($__phpunit_count > 0) {
            $__phpunit_arguments_tmp = func_get_args();

            for ($__phpunit_i = 0; $__phpunit_i < $__phpunit_count; $__phpunit_i++) {
                $__phpunit_arguments[] = $__phpunit_arguments_tmp[$__phpunit_i];
            }
        }

        $__phpunit_result = $this->__phpunit_getInvocationMocker()->invoke(
            new \PHPUnit\Framework\MockObject\Invocation\ObjectInvocation(
                'Foo', 'bar', $__phpunit_arguments, 'Closure', $this, true
            )
        );

        return $__phpunit_result;
    }

    public function expects(\PHPUnit\Framework\MockObject\Matcher\Invocation $matcher)
    {
        return $this->__phpunit_getInvocationMocker()->expects($matcher);
    }

    public function method()
  