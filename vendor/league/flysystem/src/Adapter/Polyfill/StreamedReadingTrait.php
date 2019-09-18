       if ($arg % 2 == 0) {
            return true;
        }
        return false;
    });

    $mock->foo(4); // matches the expectation
    $mock->foo(3); // throws a NoMatchingExpectationException

Any, or no arguments
^^^^^^^^^^^^^^^^^^^^

We can declare that the expectation matches a method call regardless of what
arguments are passed:

.. code-block:: php

    $mock = \Mockery::mock('MyClass');
    $mock->shouldReceive('name_of_method')
        ->withAnyArgs();

This is set by default unless otherwise specified.

We can declare that the expectation matches method calls with zero arguments:

.. code-block:: php

    $mock = \Mockery::mock('MyClass');
    $mock->shouldReceive('name_of_method')
        ->withNoArgs();

Declaring Return Value Expectations
-----------------------------------

For mock objects, we can tell Mockery what return values to return from the
expected m