--TEST--
\PHPUnit\Framework\MockObject\Generator::generate('Foo', [], 'MockFoo', true, true)
--FILE--
<?php
require __DIR__ . '/../../../../vendor/autoload.php';

$generator = new \PHPUnit\Framework\MockObject\Generator;

$mock = $generator->generate(
    '\NS\Foo',
    [],
    'MockFoo',
    true,
    true
);

print $mock['code'];
?>
--EXPECT--
namespace NS {

class Foo
{
}

}

namespace {

class MockFoo extends NS\Foo implements PHPUnit\Framework\MockObject\MockObject
{
    private $__phpunit_invocationMocker;
    private $__phpunit_originalObject;
    private $__phpunit_configurable = [];
    private $__phpunit_returnValueGeneration = true;

    public function __clone()
    {
        $this->__phpunit_invocationMocker = clone $this->__phpunit_getInvocationMocker();
    }

    public function expects(\PHPUnit\Framework\MockObject\Matcher\Invocation $matcher)
    {
        return $this->__ph