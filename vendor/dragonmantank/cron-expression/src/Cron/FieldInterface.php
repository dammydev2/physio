> I also make [Caret](https://caret.io?ref=parsedown) - a Markdown editor for Mac and PC.

## Parsedown

[![Build Status](https://img.shields.io/travis/erusev/parsedown/master.svg?style=flat-square)](https://travis-ci.org/erusev/parsedown)
<!--[![Total Downloads](http://img.shields.io/packagist/dt/erusev/parsedown.svg?style=flat-square)](https://packagist.org/packages/erusev/parsedown)-->

Better Markdown Parser in PHP

[Demo](http://parsedown.org/demo) |
[Benchmarks](http://parsedown.org/speed) |
[Tests](http://parsedown.org/tests/) |
[Documentation](https://github.com/erusev/parsedown/wiki/)

### Features

* One File
* No Dependencies
* Super Fast
* Extensible
* [GitHub flavored](https://help.github.com/articles/github-flavored-markdown)
* Tested in 5.3 to 7.1 and in HHVM
* [Markdown Extra extension](https://github.com/erusev/parsedown-extra)

### Installation

Include `Parsedown.php` or install [the composer package](https://packagist.org/packages/erusev/parsedown).

### Example

``` php
$Parsedown = new Parsedown();

echo $Parsedown->text('Hello _Parsedown_!'); # prints: <p>Hello <em>Parsedown</em>!</p>
```

More examples in [the 