ame', 'message');

.. _expectations-setting-public-properties:

Setting Public Properties
-------------------------

Used with an expectation so that when a matching method is called, we can cause
a mock object's public property to be set to a specified value, by using
``andSet()`` or ``set()``:

.. code-block:: php

    $mock = \Mockery::mock('MyClass');
    $mock->shouldReceive('name_of_method')
        ->andSet($property, $value);
    // or
    $mock->shouldReceive('name_of_method')
        ->set($property, $value);

In cases where we want to call the real method of the class that was mocked and
return its result, the ``passthru()`` method tells the expectation to bypass
a return queue:

.. code-block:: php

    passthru()

It allows expectation matching and call count validation to be applied against
real methods while still calling the real class method with the expected
arguments.

Declaring Call Count Expectations
---------------------------------

Besides setting expectations on the arguments of the method calls, and the
return values of those same calls, we can set expectations on how many times
should any method be called.

When a call count expectation is not met, a
``\Mockery\Expectation\InvalidCountException`` will be thrown.

.. note::

    It is absolutely required to call ``\Mockery::close()`` at the end of our
    tests, for example in the ``tearDown()`` method of PHPUnit. Otherwise
    Mockery will not verify the calls made against our mock objects.

We can declare that the expected method may be called zero or more times:

.