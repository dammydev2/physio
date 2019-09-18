ecific order in
relation to similarly marked methods.

.. code-block:: php

    ordered()

The order is dictated by the order in which this modifier is actually used when
setting up mocks.

Declares the method as belonging to an order group (which can be named or
numbered). Methods within a group can be called in any order, but the ordered
calls from outside the group are ordered in relation to the group:

.. code-block:: php

    ordered(group)

We can set up so that method1 is called before group1 which is in turn called
before method2.

When called prior to ``ordered()`` or ``ordered(group)``, it declares this
ordering to apply across all mock objects (not just the current mock):

.. code-block:: php

    globally()

This allows for dictating order expectations across multiple mocks.

The ``byDefault()`` marks an expectation as a default. Default expectations are
applied unless a non-default expectation is created:

.. code-block:: php

    byDefault()

These later expectations immediately replace the previously defined de