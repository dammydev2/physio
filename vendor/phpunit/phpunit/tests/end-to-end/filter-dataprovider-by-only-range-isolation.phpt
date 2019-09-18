--TEST--
\PHPUnit\Framework\MockObject\Generator::generate('ClassWithMethodWithVariadicArguments', [], 'MockFoo', true, true)
--FILE--
<?php
class ClassWithMethodWithVariadicArguments
{
    public function methodWithVariadicArguments($a, ...$parameters)
    {
    }
}

require __DIR__ . '/../../../../vendor/autoload.php';

$generator = new \PHPUnit\Framework\MockObject\Generator;

$mock = $generator->generate(
    'ClassWithMethodWithVariadicArguments',
    [],
    'MockFoo',
    true,
    true
);

print $mock['code'];
?>
--EXPECT--
class MockFoo extends ClassWithMethodWithVariadicArguments implements PHPUnit\Framework\MockObject\MockObject
{
    private $__phpuni