.. index::
    single: Mocking; Partial Mocks

Creating Partial Mocks
======================

Partial mocks are useful when we only need to mock several methods of an
object leaving the remainder free to respond to calls normally (i.e.  as
implemented). Mockery implements three distinct strategies for creating
partials. Each has specific advantages and disadvantages so which strategy we
use will depend on our own preferences and the source code in need of
mocking.

We have previously talked a bit about :ref:`creating-test-doubles-partial-test-doubles`,
but we'd like to expand on the subject a bit here.

#. Runtime partial test doubles
#. Generated partial test doubles
#. Proxied Partial Mock

Runtime partial test doubles
----------------------------

A runtime partial test double, also known as a passive partial mock, is a kind
of a default state of being for a mocked object.

.. code-block:: php

    $mock = \Mockery::mock('MyClass')->makePartial();

With a runtime partial, we assume that all methods will simply defer to the
parent class (``MyClass``) original methods unless a method call matches a
known expectation. If we have no matching expectation for a specific method
call, that call is deferred to the class being mocked. Since the division
between mocked and unmocked calls depends entirely on the expectations we
define, there is no need to define which methods to mock in advance.

See the cookbook entry on :doc:`../coo