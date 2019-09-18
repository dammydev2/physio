--TEST--
\PHPUnit\Framework\MockObject\Generator::generate('ClassWithFinalMethod', [], 'MockFoo', true, true)
--FILE--
<?php
class ClassWithFinalMethod
{
    final public function finalMethod()
    {
    }
}

require __DIR__ . '/../../../../vendor/autoload.php';

$generator = new \PHPUnit\Framework\MockObject\Generator;

$mock = $generator->generate(
    'ClassWithFinalMethod',
    [],
    'MockFoo',
    true,
    true
);

print $mock['code'];
?>
--EXPECT--
class MockFoo extends ClassWithFinalMethod implements PHPUnit\Framework\MockObject\MockObject
{
    private $__phpunit_invocationMocker;
    private $__phpunit_originalObject;
    private $__phpunit_configurable = [];
    private $__phpunit_returnValue