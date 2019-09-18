yTest_MethodParamRef
{
    public function method1(&$foo)
    {
        return true;
    }
}
class MockeryTest_MethodParamRef2
{
    public function method1(&$foo)
    {
        return true;
    }
}
class MockeryTestRef1
{
    public function foo(&$a, $b)
    {
    }
}

class MockeryTest_PartialNormalClass
{
    public function foo()
    {
        return 'abc';
    }

    public function bar()
    {
        return 'abc';
    }
}

abstract class MockeryTest_PartialAbstractClass
{
    abstract public function foo();

    public function bar()
    {
        return 'abc';
    }
}

class MockeryTest_PartialNormalClass2
{
    public function foo()
    {
        return 'abc';
    }

    public function bar()
    {
        return 'abc';
    }

    public function baz()
    {
        return 'abc';
    }
}

abstract class MockeryTest_PartialAbstractClass2
{
    abstract public function foo();

    public function bar()
    {
        return 'abc';
    }

    abstract public function baz();
}

class MockeryTest_TestInheritedType
{
}

if (PHP_VERSION_ID >= 50400) {
    class MockeryTest_MockCallableTypeHint
    {
        public function foo(callable $baz)
        {
            $baz();
        }

        public function bar(callable $callback = null)
        {
            $callback();
        }
    }
}

class MockeryTest_WithToString
{
    public function __toString()
    {
    }
}

class MockeryTest_ImplementsIteratorAggregate implements IteratorAggregate
{
    public function getIterator()
    {
        return new ArrayIterator(array());
    }
}

class MockeryTest_ImplementsIterator implements Iterator
{
    public function rewind()
    {
    }

    public function current()
    {
    }

    public function key()
    {
    }

    public function next()
    {
    }

    public function valid()
    {
    }
}

class EmptyConstructorTest
{
    public $numberOfConstructorArgs;

    public function __construct(...$args)
    {
        $this->numberOfConstructorArgs = count($args);
    }

    public function foo()
    {
    }
}

interface MockeryTest_InterfaceWithMethodParamSelf
{
    public function foo(self $bar);
}

class MockeryTest_Lowercase_ToString
{
    public function __tostring()
    {
    }
}

class MockeryTest_PartialStatic
{
    public static function mockMe($a)
    {
        return $a;
    }

    public static function keepMe($b)
