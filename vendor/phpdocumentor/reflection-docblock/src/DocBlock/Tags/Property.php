1.8.0 / 2018/08/05
==================

* Support for void return types without explicit will (@crellbar)
* Clearer error message for unexpected method calls (@meridius)
* Clearer error message for aggregate exceptions (@meridius)
* More verbose `shouldBeCalledOnce` expectation (@olvlvl)
* Ability to double Throwable, or methods that extend it (@ciaranmcnulty)
* [fixed] Doubling methods where class has additional arguments to interface (@webimpress)
* [fixed] Doubling methods where arguments are nullable but default is not null (@webimpress)
* [fixed] Doubling magic methods on parent class (@dsnopek)
* [fixed] Check method predictions only once (@dontub)
* [fixed] Argument::containingString throwing error when called with non-string (@dcabrejas)

1.7.6 / 2018/04/18
==================

* Allow sebastian/comparator ^3.0 (@sebastianbergmann)

1.7.5 / 2018/02/11
==================

* Support for object return type hints (thanks @greg0ire)

1.7.4 / 2018/02/11
==================

* Fix issues with PHP 7.2 (thanks @greg0ire)
* Support object type hints in PHP 7.2 (thanks @@jansvoboda11)

1.7.3 / 2017/11/24
==================

* Fix SplInfo ClassPatch to work with Symfony 4 (Thanks @gnugat)

1.7.2 / 2017-10-04
==================

* Reverted "check method predictions only once" due to it breaking Spies

1.7.1 / 2017-10-03
==================

* Allow PHP5 keywords methods generation on PHP7 (thanks @bycosta)
* Allow reflection-docblock v4 (thanks @GrahamCampbell)
* Check method predictions only once (thanks @dontub)
* Escape file path sent to \SplFileObjectConstructor when running on Windows (thanks @danmartin-epiphany)

1.7.0 / 2017-03-02
==================

* Add full PHP 7.1 Support (thanks @prolic)
* Allow `sebastian/comparator ^2.0` (thanks @sebastianbergmann)
* Allow `sebastian/recursion-context ^3.0` (thanks @sebastianbergmann)
* Allow `\Error` instances in `ThrowPromise` (thanks @jameshalsall)
* Support `phpspec/phpspect ^3.2` (thanks @Sam-Burns)
* Fix failing builds (thanks @Sam-Burns)

1.6.2 / 2016-11-21
==================

* Added support for detecting @method on interfaces that the class itself implements, or when the stubbed class is an interface itself (thanks @Seldaek)
* Added support for sebastian/recursion-context 2 (thanks @sebastianbergmann)
* Added testing on PHP 7.1 on Travis (thanks @danizord)
* Fixed the usage of the phpunit comparator (thanks @Anyqax)

1.6.1 / 2016-06-07
==================

  * Ignored empty method names in invalid `@method` phpdoc
  * Fixed the mocking of SplFileObject
  * Added compatibility with phpdocumentor/reflection-docblock 3

1.6.0 / 2016-02-15
==================

  * Add Variadics support (thanks @pamil)
  * Add ProphecyComparator for comparing objects that need revealing (thanks @jon-acker)
  * Add ApproximateValueToken (thanks @dantleech)
  * Add support for 'self' and 'parent' return type (thanks @bendavies)
  * Add __invoke to allowed reflectable methods list (thanks @ftrrtf)
  * Updated ExportUtil to reflect the latest changes by Sebastian (thanks @jakari)
  * Specify the required php version for composer (thanks @jakzal)
  * Exclude 'args' in the generated backtrace (thanks @oradwell)
  * Fix code generation for scalar parameters (thanks @trowski)
  * Fix missing sprintf in Inval