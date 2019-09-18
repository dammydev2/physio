.. index::
    single: Expectations

Expectation Declarations
========================

.. note::

    In order for our expectations to work we MUST call ``Mockery::close()``,
    preferably in a callback method such as ``tearDown`` or ``_before``
    (depending on whether or not we're integrating Mockery with another
    framework). This static call cleans up the Mockery container used by the
    current test, and run any verification tasks needed for our expectations.

Once we have created a mock object, we'll often want to start defining how
exactly it should behave (and how it should be called). This is where the
Mockery expectation declarations take over.

Declaring Method Call Expectations
----------------------------------

To tell our test double to expect a call for a method with a given name, we use
the ``shouldReceive`` method:

.. code-block:: php

    $mock = \Mockery::mock('MyClass');
    $mock->shouldReceive('name_of_method');

This is the starting expectation upon which all other expectations and
constraints are appended.