ted, $className)
    {
        $container = new \Mockery\Container;
        $this->assertSame($expected, $container->isValidClassName($className));
    }

    public function classNameProvider()
    {
        return array(
            array(false, ' '), // just a space
            array(false, 'ClassName.WithDot'),
            array(false, '\\\\TooManyBackSlashes'),
            array(true,  'Foo'),
            array(true,  '\\Foo\\Bar'),
        );
    }
}

class MockeryTest_CallStatic
{
    public static function __callStatic($method, $args)
    {
    }
}

class MockeryTest_ClassMultipleConstructorParams
{
    public function __construct($a, $b)
    {
    }

    public function dave()
    {
    }
}

interface MockeryTest_InterfaceWithTraversable extends ArrayAccess, Traversable, Countable
{
    public function self();
}

class MockeryTestIsset_Bar
{
    public function doSomething()
    {
    }
}

class MockeryTestIsset_Foo
{
    private $var;

    public function __construct($var)
    {
        $this->var = $var;
    }

    public function __get($name)
    {
        $this->var->doSomething();
    }

    public function __isset($name)
    {
        return (bool) strlen($this->__get($name));
    }
}

class MockeryTest_IssetMethod
{
    protected $_properties = array();

    public function __construct()
    {
    }

    public function __isset($property)
    {
        return isset($this->_properties[$property]);
    }
}

class MockeryTest_UnsetMethod
{
    protected $_properties = array();

    public function __construct()
    {
    }

    public function __unset($property)
    {
        unset($this->_properties[$property]);
    }
}

class MockeryTestFoo
{
    public function foo()
    {
        return 'foo';
    }
}

class MockeryTestFoo2
{
    public function foo()
    {
        return 'foo';
    }

    public function bar()
    {
        return 'bar';
    }
}

final class MockeryFoo3
{
    public function foo()
    {
        return 'baz';
    }
}

class MockeryFoo4
{
    final public function foo()
    {
        return 'baz';
    }

    public function bar()
    {
        return 'bar';
    }
}

interface MockeryTest_Interface
{
}
interface MockeryTest_Interface1
{
}
interface MockeryTest_Interface2
{
}

interface MockeryTest_InterfaceWithAbstractMethod
{
    public function set();
}

interface MockeryTest_InterfaceWithPublicStaticMethod
{
    public static function self();
}

abstract class MockeryTest_AbstractWithAbstractMethod
{
    abstract protected function set();
}

class MockeryTest_WithProtectedAndPrivate
{
    protected function protectedMethod()
    {
    }

    private function privateMethod()
    {
    }
}

class MockeryTest_ClassConstructor
{
    public function __construct($param1)
    {
    }
}

class MockeryTest_ClassConstructor2
{
    protected $param1;

    public function __construct(stdClass $param1)
    {
        $this->param1 = $param1;
    }

    public function getParam1()
    {
        return $this->param1;