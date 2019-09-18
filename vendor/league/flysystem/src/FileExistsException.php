 = Mockery::mock(BookRepository::class, [
    "findAll" => [new Book(), new Book()],
]);
```

## Method Call Expectations 📲

A Method call expectation is a mechanism to allow you to verify that a
particular method has been called. You can specify the parameters and you can
also specify how many times you expect it to be called. Method call expectations
are used to verify indirect output of the system under test.

``` php
$book = new Book();

$double = Mockery::mock(BookRepository::class);
$double->expects()->add($book);
```

During the test, Mockery accept calls to the `add` method as prescribed.
After you have finished exercising the system under test, you need to
tell Mockery to check 