with `nullOr*()` to run the
assertion only if it the value is not `null`:

```php
Assert::nullOrString($middleName, 'The middle name must be a string or null. Got: %s');
```

Authors
-------

* [Bernhard Schussek] a.k.a. [@webmozart]
* [The Community Contributors]

Contribute
----------

Contributions to the package are always welcome!

* Report any bugs or issues you find on the [issue tracker].
* You can grab the source code at the package's [Git repository].

Support
-------

If you are having problems, send a mail to bschussek@gmail.com or shout out to
[@webmozart] on Twitter.

License
-------

All contents of this package are licensed under the [MIT license].

[beberlei/assert]: https://github.com/beberlei/assert
