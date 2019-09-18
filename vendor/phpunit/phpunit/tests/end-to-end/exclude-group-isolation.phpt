--TEST--
\PHPUnit\Framework\MockObject\Generator::generate('Foo', [], 'MockFoo', true)
--FILE--
<?php
interface IFoo
{
    public function __construct($bar);
}

class Foo implements IFoo
{
    public function __construct($bar)
    {
    }
}

require __DIR__ . '/../../../../vendor/autoload.php';

$generator = new \PHPUnit\Framework\MockObject\Generator;

$mock = $generator->generate(
    'Foo',
    [],
    'MockFoo',
    true
);

print $mock['code'];
?>
--EXPECT--
class MockFoo extends Foo implements PHPUnit\Framework\MockObject\MockObject
{
    private $__phpunit_invocationMocker;
    private $__phpunit_originalObject;
    private $__phpunit_configurable = [];
    private $__phpunit_