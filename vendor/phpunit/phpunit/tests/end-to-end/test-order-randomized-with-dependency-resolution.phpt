--TEST--
\PHPUnit\Framework\MockObject\Generator::generate('Foo', [], 'MockFoo', true, true)
--FILE--
<?php
class Foo
{
    public function bar(string $baz)
    {
    }
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
class MockFoo extends Foo implements PHPUnit\Framework\MockObject\MockObject
{
    private $__phpunit_invocationMocker;
    private $__phpunit_originalObject;
    private $__phpunit_configurable = ['bar'];
    private $__phpunit_returnValueGeneration = true;

    public function __clone()
 