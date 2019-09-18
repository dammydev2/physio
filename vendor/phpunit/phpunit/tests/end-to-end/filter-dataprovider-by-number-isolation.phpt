--TEST--
\PHPUnit\Framework\MockObject\Generator::generate('ClassWithMethodWithVariadicArguments', [], 'MockFoo', true, true)
--FILE--
<?php
class ClassWithMethodWithTypehintedVariadicArguments
{
    public function methodWithTypehintedVariadicArguments($a, string ...$parameters)
    {
    }
}

require __DIR__ . '/../../../../vendor/autoload.php';

$generator = new \PHPUnit\Framework\MockObject\Generator;

$mock = $generator->generate(
    'ClassWithMethodWithTypehintedVariadicArguments',
    [],
    'MockFoo',
    true,
    true
);

print $mock['code'];
?>
--EXPECT--
class MockFoo extends ClassWithMethodWithTypehintedVariadicArguments implements PHPUnit\Framework\MockObj