ject double, which doesn't have any expectations about the object behavior,
but when put in specific environment, behaves in specific way. Ok, I know, it's cryptic,
but bear with me for a minute. Simply put, a stub is a dummy, which depending on the called
method signature does different things (has logic). To create stubs in Prophecy:

```php
$prophecy->read('123')->willReturn('value');
```

Oh wow. We've just made an arbitrary call on the object prophecy? Yes, we did. And this
call returned us a new object instance of class `MethodProphecy`. Yep, that's a specific
method with arguments prophecy. Method prophecies give you the ability to create method
promises or predictions. We'll talk about method predictions later in the _Mocks_ section.

#### Promises

Promises are logical blocks, that represent your fictional methods in prophecy terms
and they are handled by the `MethodProphecy::will(PromiseInterface $promise)` method.
As a matter of fact, the call that we made earlier (`willReturn('value')`) is a simple
shortcut to:

```php
$prophecy->read('123')->will(new Prophecy\Promise\ReturnPromise(array('value')));
```

This promise will cause any call to our double's `read()` method with exactly one
argument - `'123'` to always return `'value'`. But that's only for this
promise, there's plenty others you can use:

- `ReturnPromise` or `->willReturn(1)` - returns a value from a method call
- `ReturnArgumentPromise` or `->willReturnArgument($index)` - returns the nth method argument from call
- `ThrowPromise` or `->willThrow($exception)` - causes the method to throw specific exception
- `CallbackPromise` or `->will($callback)` - gives you a quick way to define your own custom logic

Keep in mind, that you can always add even more promises by implementing
`Prophecy\Promise\PromiseInterface`.

#### Method prophecies idempotency

Prophecy enforces same method prophecies and, as a consequence, same promises and
predictions for the same method calls with the same arguments. This means:

```php
$methodProphecy1 = $prophecy->read('123');
$methodProphecy2 = $prophecy->read('123');
$methodProphecy3 = $prophecy->read('321');

$methodProphecy1 === $methodProphecy2;
$methodProphecy1 !== $methodProphecy3;
```

That's interesting, right? Now you might ask me how would you define more complex
behaviors where some method call changes behavior of others. In PHPUnit or Mockery
you do that by predicting how many times your method will be called. In Prophecy,
you'll use promises for that:

```php
$user->getName()->willReturn(null);

// For PHP 5.4
$user->setName('everzet')->will(function () {
    $this->getName()->willReturn('everzet');
});

// For PHP 5.3
$user->setName('everzet')->will(function ($args, $user) {
    $user->getName()->willReturn('everzet');
});

// Or
$user->setName('everzet')->will(function ($args) use ($user) {
    $user->getName()->willReturn('everzet');
});
```

And now it does