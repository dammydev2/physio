# Prophecy

[![Stable release](https://poser.pugx.org/phpspec/prophecy/version.svg)](https://packagist.org/packages/phpspec/prophecy)
[![Build Status](https://travis-ci.org/phpspec/prophecy.svg?branch=master)](https://travis-ci.org/phpspec/prophecy)

Prophecy is a highly opinionated yet very powerful and flexible PHP object mocking
framework. Though initially it was created to fulfil phpspec2 needs, it is flexible
enough to be used inside any testing framework out there with minimal effort.

## A simple example

```php
<?php

class UserTest extends PHPUnit_Framework_TestCase
{
    private $prophet;

    public function testPasswordHashing()
    {
        $hasher = $this->prophet->prophesize('App\Security\Hasher');
        $user   = new App\Entity\User($hasher->reveal());

        $hasher->generateHash($user, 'qwerty')->willReturn('hashed_pass');

        $user->setPassword('qwerty');

        $this->assertEquals('hashed_pass', $user->getPassword());
    }

    protected function setup()
    {
        $this->prophet = new \Prophecy\Prophet;
    }

    protected function tearDown()
    {
        $this->prophet->checkPredictions();
    }
}
```

## Installation

### Prerequisites

Prophecy requires PHP 5.3.3 or greater.

### Setup through composer

First, add Prophecy to the list of dependencies inside your `composer.json`:

```json
{
    "require-dev": {
        "phpspec/prophecy": "~1.0"
    }
}
```

Then simply install it with composer:

```bash
$> composer install --prefer-dist
```

You can read more about Composer on its [official webpage](http://getcomposer.org).

## How to use it

First of all, in Prophecy every word has a logical meaning, even the name of the library
itself (Prophecy). When you start feeling that, you'll become very fluid with this
tool.

For example, Prophecy has been named that way because it concentrates on describing the future
behavior of objects with very limited knowledge about them. But as with any other prophecy,
those object prophecies can't create themselves - there should be a Prophet:

```php
$prophet = new Prophecy\Prophet;
```

The Prophet creates prophecies by *prophesizing* them:

```php
$prophecy = $prophet->prophesize();
```

The result of the `prophesize()` method call is a new object of class `ObjectProphecy`. Yes,
that's your specific object prophecy, which describes how your object would behave
in the near future. But first, you need to specify which object you're talking about,
right?

```php
$prophecy->willExtend('stdClass');
$prophecy->willImplement('SessionHandlerInt