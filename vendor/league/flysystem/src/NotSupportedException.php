.. index::
    single: Cookbook; Complex Argument Matching With Mockery::on

Complex Argument Matching With Mockery::on
==========================================

When we need to do a more complex argument matching for an expected method call,
the ``\Mockery::on()`` matcher comes in really handy. It accepts a closure as an
argument and that closure in turn receives the argument passed in to the method,
when called. If the closure returns ``true``, Mockery will consider that the
argument has passed the expectation. If the closure returns ``false``, or a
"falsey" value, the expectation will not pass.

The ``\Mockery::on()`` matcher can be used in various scenarios — validating
an array argument based on multiple keys and values, complex string matching...

Say, 