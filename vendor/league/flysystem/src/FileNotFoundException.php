ve a global function called `spy`, Mockery will
silently skip the declaring its own `spy` function.

### Testing Traits

As Mockery ships with code generation capabilities, it was trivial to add
functionality allowing users to create objects on the fly that use particular
traits. Any abstract methods defined by the trait will be created and can have
expectations or stubs configured like normal Test Doubles.

``` php
trait Foo {
    function foo() {
        return $this->doFoo();
    }

    abstract function doFoo();
}

$double = Mockery::mock(Foo::class);
$double->allows()->doFoo()->andReturns(123);
$double->foo(); // int(123)
```

### Testing the constructor arguments of hard Depen