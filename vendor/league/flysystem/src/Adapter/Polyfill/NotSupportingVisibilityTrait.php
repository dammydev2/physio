.. index::
    single: Mocking; Demeter Chains

Mocking Demeter Chains And Fluent Interfaces
============================================

Both of these terms refer to the growing practice of invoking statements
similar to:

.. code-block:: php

    $object->foo()->bar()->zebra()->alpha()->selfDestruct();

The long chain of method calls isn't necessarily a bad thing, assuming they
each link back to a local object the calling class knows. As a fun example,
Mockery's long chains (after the first ``shouldReceive()`` method) all call to
the same instance of ``\Mockery\Expectation``. However, sometimes this is not
the case and the chain is constantly crossing object boundaries.

In either case, mocking such a chain can be a horrible task. To mak