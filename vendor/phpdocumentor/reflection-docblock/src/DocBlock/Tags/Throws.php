nt::exact('everzet'));
```

`ExactValueToken` is not very useful in our case as it forced us to hardcode the username.
That's why Prophecy comes bundled with a bunch of other tokens:

- `IdenticalValueToken` or `Argument::is($value)` - checks that the argument is identical to a specific value
- `ExactValueToken` or `Argument::exact($value)` - checks that the argument matches a specific value
- `TypeToken` or `Argument::type($typeOrClass)` - checks that the argument matches a specific type or
  classname
- `ObjectStateToken` or `Argument::which($method, $value)` - checks that the argument method returns
  a specific value
- `CallbackToken` or `Argument::that(callback)` - checks that the argument matches a custom callback
- `AnyValueToken` or `Argument::any()` - matches any argument
- `AnyValuesToken` or `Argument::cetera()` - matches any arguments to the rest of the signature
- `StringContainsToken` or `Argument::containingString($value)` - checks that the argument contains a specific string value

And you can add even more by implementing `TokenInterface` with your own custom classes.

So, let's refactor our initial `{set,get}Name()` logic with argument tokens:

```php
use Prophecy\Argument;

$user->getName()->willReturn(null);

// For PHP 5.4
$user->setName(Argument::type('string'))->will(function ($args) {
    $this->getName()->willReturn($args[0]);
});

// For PHP 5.3
$user->setName(Argument::type('string'))->will(function ($args, $user) {
    $user->getName()->willReturn($args[0]);
});

// Or
$user->setName(Argument::type('string'))->will(function ($args) use ($user) {
    $user->getName()->willReturn($args[0]);
});
```

That's it. Now our `{set,get}Name()` prophecy will work with any string argument provided to it.
We've just described how our stub object should behave, even though the original object could have
no behavior whatsoever.

One last bit about arguments now. You might ask, what happe