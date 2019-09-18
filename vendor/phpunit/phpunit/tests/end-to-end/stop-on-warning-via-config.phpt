--TEST--
\PHPUnit\Framework\MockObject\Generator::generate('Bar', [], 'MockBar', true, true)
--FILE--
<?php
abstract class Foo
{
    abstract public function baz();
}

class Bar extends Foo
{
    public function baz(): parent
    {
    }
}

require __DIR__ . '/../../../../vendor/autoload.php';

$generator = new \PHPUnit\Framework\MockObject\Generator;

$mock = $generator->generate(
    'Bar',
    [],
    'MockBar',
    true,
    true
);

print $mock['code'];
--EXPECT--
class MockBar extends Bar implements PHPUnit\Framework\MockObject\MockObject
{
    private $__phpunit_invocationMocker;
    private $__phpunit_originalObject;
    private $__phpunit_configurable = ['baz'];
    private $__phpunit_returnValueGene