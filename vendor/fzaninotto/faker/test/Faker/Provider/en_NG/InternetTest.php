== Version 2.0: Released XXX XX 2016 ==

* Removed automatic loading of global functions

== Version 1.1.0: Released Feb 2 2012 ==

Issues Fixed: 121, 138, 147

* Added non-empty matchers to complement the emptiness-matching forms.

  - nonEmptyString()
  - nonEmptyArray()
  - nonEmptyTraversable()

* Added ability to pass variable arguments to several array-based matcher
  factory methods so they work like allOf() et al.

  - anArray()
  - arrayContainingInAnyOrder(), containsInAnyOrder()
  - arrayContaining(), contains()
  - stringContainsInOrder()

* Matchers that accept an array of matchers now also accept variable arguments.
  Any non-matcher arguments are wrapped by IsEqual.

* Added noneOf() as a shortcut for not(