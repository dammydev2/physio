.. index::
    single: Argument Validation

Argument Validation
===================

The arguments passed to the ``with()`` declaration when setting up an
expectation determine the criteria for matching method calls to expectations.
Thus, we can setup up many expectations for a single method, each
differentiated by the expected arguments. Such argument matching is done on a
"best fit" basis.  This ensures explicit matches take precedence over
generalised matches.

An explicit match is merely where the expected argument and the actual
argument are easily equated (i.e. using ``===`` or ``==``). More generalised
matches are possible using regular expressions, class hinting and the
available generic matchers. The purpose of generalised matchers is to allow
arguments be defined in non-explicit terms, e.g. ``Mockery::any()`` passed to
``with()`` will match **any** argument in that position.

Mockery's generic matchers do n