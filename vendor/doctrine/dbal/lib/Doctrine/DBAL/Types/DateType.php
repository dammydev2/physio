PHP Cron Expression Parser
==========================

[![Latest Stable Version](https://poser.pugx.org/dragonmantank/cron-expression/v/stable.png)](https://packagist.org/packages/dragonmantank/cron-expression) [![Total Downloads](https://poser.pugx.org/dragonmantank/cron-expression/downloads.png)](https://packagist.org/packages/dragonmantank/cron-expression) [![Build Status](https://secure.travis-ci.org/dragonmantank/cron-expression.png)](http://travis-ci.org/dragonmantank/cron-expression)

The PHP cron expression parser can parse a CRON expression, determine if it is
due to run, calculate the next run date of the expression, and calculate the previous
run date of the expression.  You can calculate dates far into the future or past by
skipping **n** number of matching dates.

The parser can handle increments of ranges (e.g. */12, 2-59/3), intervals (e.g. 0-9),
lists (e.g. 1,2,3), **W** to find the nearest weekday for a given day of the month, **L** to
find the last day of the month, **L** to find the last given weekday of a month, and hash
(#) to find the nth weekday of a given month.

More information about this fork can be found in the blog post [here](http://ctankersley.com/2017/10/12/cron-expression-update/). tl;dr - v2.0.0 is a major breaking change, and @dragonmantank can better take care of the project in a separate fork.

Installing
==========

Add the dependency to your project:

```bash
composer require dragonmantank/cron-expression
```

Us