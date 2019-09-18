--TEST--
https://github.com/sebastianbergmann/phpunit-mock-objects/issues/397
--FILE--
<?php
class C
{
    public function m(?self $other): self
    {
    }
}

require __DIR__ . '/../../../../vendor/autoload.php';

$generator = new \PHPUnit\Framework\MockObject\Generator;

$mock = $generator->generate(
    C::class,
    [],
    'MockC',
    true,
    true
);

print $mock['code'];
--EXPECT--
class MockC extends C implements PHPUnit\Framework\MockObject\MockObject
{
    private $__phpunit_invocationMocker;
    private $__phpunit_originalObject;
    private $__phpunit_configurable = ['m'];
    private $__phpunit_returnValueGeneration = true;

    public function __clone()
    {
        $this->__phpunit_invocationMocker = clone $this->__phpunit_getInvocationMocker();
    }

    public function m(?C $other): C
    {
        $__phpunit_arguments = [$other];
        $__phpunit_count     = func_num_args();

        if ($__phpunit_count > 1) {
       