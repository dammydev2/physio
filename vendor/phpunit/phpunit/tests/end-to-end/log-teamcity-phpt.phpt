--TEST--
\PHPUnit\Framework\MockObject\Generator::generate('Foo', null, 'ProxyFoo', true, true, true, true)
--FILE--
<?php
class Foo
{
    public function bar(Foo $foo)
    {
    }

    public function baz(Foo $foo)
    {
    }
}

require __DIR__ . '/../../../../vendor/autoload.php';

$generator = new \PHPUnit\Framework\MockObject\Generator;

$mock = $generator->generate(
    'Foo', [], 'ProxyFoo', true, true, true, true
);

print $mock['code'];
?>
--EXPECT--
class ProxyFoo extends Foo implements PHPUnit\Framework\MockObject\MockObject
{
    private $__phpunit_invocationMocker;
    private $__phpunit_originalObject;
    private $__phpunit_configurable = ['bar', 'baz'];
    private $__phpunit_returnValueGeneration = true;

    public function __clone()
    