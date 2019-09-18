# Changes in sebastianbergmann/environment

All notable changes in `sebastianbergmann/environment` are documented in this file using the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## [4.2.1] - 2019-04-25

* Fixed an issue in `Runtime::getCurrentSettings()`

## [4.2.0] - 2019-04-25

### Added

* Implemented [#36](https://github.com/sebastianbergmann/environment/pull/36): `Runtime::getCurrentSettings()`

## [4.1.0] - 2019-02-01

### Added

* Implemented `Runtime::getNameWithVersionAndCodeCoverageDriver()` method
* Implemented [#34](https://github.com/sebastianbergmann/environment/pull/34): Support for PCOV extension

## [4.0.2] - 2019-01-28

### Fixed

* Fixed [#33](https://github.com/sebastianbergmann/environment/issues/33): `Runtime::discardsComments()` returns true too eagerly

### Removed

* Removed support for Zend 