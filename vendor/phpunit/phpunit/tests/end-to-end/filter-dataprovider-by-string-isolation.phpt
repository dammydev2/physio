--TEST--
\PHPUnit\Framework\MockObject\Generator::generate('NS\Foo', [], 'MockFoo', true)
--FILE--
<?php
namespace NS;

class Foo
{
    public function __clone()
    {
    }
}

require __DIR__ . '/../../../../vendor/autoload.php';

$generator = new \PHPUnit\Framework\MockObject\Generator;

$mock = $generator->generate(
    'NS\Foo',
    [],
    'MockFoo',
    true
);

print $mock['code'];
?>
--EXPECT--
class MockFoo extends NS\Foo implements PHPUnit\Framework\MockObject\MockObject
{
    private $__phpunit_invocationMocker;
    private $__phpunit_originalObject;
    private $__phpunit_configurable = [];
    private $__phpunit_returnValueGeneration = true;

    public function __clone()
    {
 