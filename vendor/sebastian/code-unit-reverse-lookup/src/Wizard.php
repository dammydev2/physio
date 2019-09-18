# sebastian/diff

Diff implementation for PHP, factored out of PHPUnit into a stand-alone component.

## Installation

You can add this library as a local, per-project dependency to your project using [Composer](https://getcomposer.org/):

    composer require sebastian/diff

If you only need this library during development, for instance to run your project's test suite, then you should add it as a development-time dependency:

    composer require --dev sebastian/diff

### Usage

#### Generating diff

The `Differ` class can be used to generate a textual representation of the difference between two strings:

```php
<?php
use SebastianBergmann\Diff\Differ;

$differ = new Differ;
print $differ->diff('foo', 'bar');
```

The code above yields the output below:
```diff
--- Original
+++ New
@@ @@
-foo
+bar
```

There are three output builders available in this package:

#### UnifiedDiffOutputBuilder 

This is default builder, which generates the output close to udiff and is used by PHPUnit.

```php
<?php

use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

$builder = new UnifiedDiffOutputBuilder(
    "--- Original\n+++ New\n", // custom header
    false                      // do not add line numbers to the diff 
);

$differ = new Differ($builder);
print $differ->diff('foo', 'bar');
```

#### StrictUnifiedDiffOutputBuilder

Generates (strict) Unified diff's (unidiffs) with hunks,
similar to `diff -u` and compatible with `patch` and `git apply`.

```php
<?php

use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder;

$builder = new StrictUnifiedDiffOutputBuilder([
    'collapseRanges'      => true, // ranges of length one are rendered with the trailing `,1`
    'commonLineThreshold' => 6,    // number of same lines before ending a new hunk and creating a new one (if needed)
    'contextLines'        => 3,    // like `diff:  -u, -U NUM, --unified[=NUM]`, for patch/git apply compatibility best to keep at least @ 3
    'fromFile'            => null,
    'fromFileDate'        => null,
    'toFile'              => null,
    'toFileDate'          => null,
]);

$differ = new Differ($builder);
print $differ->diff('foo', 'bar');
```

#### DiffOnlyOutputBuilder

Output only the lines that differ.

```php
<?php

use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\DiffOnlyOutputBuilder;

$builder = new DiffOnlyOutputBuilder(
    "--- Original\n+++ New\n"
);

$differ = new Differ($builder);
print $differ->diff('foo', 'bar');
```

#### DiffOutputBuilderInterface

You can pass any output builder to the `Differ` class as longs as it implements the `DiffOutputBuilderInterface`. 

#### Parsing diff

The `Parser` class can be used to parse a unified diff into an object graph:

```php
use SebastianBergmann\Diff\Parser;
use SebastianBergmann\Git;

$git = new Git('/usr/local/src/money');

$