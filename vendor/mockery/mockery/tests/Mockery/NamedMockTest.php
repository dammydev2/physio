# Using Monolog

- [Installation](#installation)
- [Core Concepts](#core-concepts)
- [Log Levels](#log-levels)
- [Configuring a logger](#configuring-a-logger)
- [Adding extra data in the records](#adding-extra-data-in-the-records)
- [Leveraging channels](#leveraging-channels)
- [Customizing the log format](#customizing-the-log-format)

## Installation

Monolog is available on Packagist ([monolog/monolog](http://packagist.org/packages/monolog/monolog))
and as such installable via [Composer](http://getcomposer.org/).

```bash
composer require monolog/monolog
```

If you do not use Composer, you can grab the code from GitHub, and use any
PSR-0 compatible autoloader (e.g. the [Symfony2 ClassLoader component](https://github.com/symfony/ClassLoader))
to load Monolog classes.

## Core Concepts

Every `Logger` instance has a channel (name) and a stack of handlers. Whenever
you add a record to the logger, it traverses the handler stack. Each handler
decides whether it fully handled the record, and if so, the propagation of the
record ends there.

This allows for flexible logging setups, for example having a `StreamHandler` at
the bottom of the stack that will log anything to disk, and on top of that add
a `MailHandler` that will send emails only when an error message is logged.
Handlers also have a `$bubble` property which defines whether they block the
record or not if they handled it. In this example, setting the `MailHandler`'s
`$bubble` argument to false means that records handled by the `MailHandler` will
not propagate to the `StreamHandler` anymore.

You can create many `Logger`s, each defining a channel (e.g.: db, request,
router, ..) and each of them combining various handlers, which can be shared
or not. The channel is reflected in the logs and allows you to easily see or
filter records.

Each Handler also has a Formatter, a default one with settings that make sense
will be created if you don't set one. The formatters normalize and format
incoming records so that they can be used by the handlers to output useful
information.

Custom severity levels are not available. Only the eight
[RFC 5424](http://tools.ietf.org/html/rfc5424) levels (debug, info, notice,
warning, error, critical, alert, emergency) are present for basic filtering
purposes, but for sorting and other use cases that would require
flexibility, you should add Processors to the Logger that can add extra
information (tags, user ip, ..) to the records before they are handled.

## Log Levels

Monolog supports the logging levels described by [RFC 5424](http://tools