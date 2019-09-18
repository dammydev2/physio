# Version

Library for handling version information and constraints

[![Build Status](https://travis-ci.org/phar-io/version.svg?branch=master)](https://travis-ci.org/phar-io/version)

## Installation

You can add this library as a local, per-project dependency to your project using [Composer](https://getcomposer.org/):

    composer require phar-io/version

If you only need this library during development, for instance to run your project's test suite, then you should add it as a development-time dependency:

    composer require --dev phar-io/version

## Version constraints

A Version constraint describes a range of versions or a discrete version number. The format of version numbers follows the schema of [semantic versioning](http://semver.org): `<major>.<minor>.<patch>`. A constraint might contain an operator that describes the range.

Beside the typical mathematical operators like `<=`, `>=`, there are two special operators:

*Caret operator*: `^1.0`
can be written as `>=1.0.0 