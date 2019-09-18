# Version

**Version** is a library that helps with managing the version number of Git-hosted PHP projects.

## Installation

You can add this library as a local, per-project dependency to your project using [Composer](https://getcomposer.org/):

    composer require sebastian/version

If you only need this library during development, for instance to run your project's test suite, then you should add it as a development-time dependency:

    composer require --dev sebastian/version

## Usage

The constructor of the `SebastianBergmann\Version` class expects two parameters:

* `$release` is the version number of the latest release (`X.Y.Z`, for instance) or the name of the release series (`X.Y`) when no release has been made from that branch / for that release series yet.
* `$path` is the path to the directory (or a subdirectory thereof) where the sourcecode of the project can be found. Simply passing `__DIR