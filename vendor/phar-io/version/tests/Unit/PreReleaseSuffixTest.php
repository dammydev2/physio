 its capability to expand partial element names into Fully Qualified Structural 
Element Names; but in order to do that we need an additional `\phpDocumentor\Reflection\Types\Context` class that will 
inform the resolver in which namespace the given expression occurs and which namespace aliases (or imports) apply.

## Resolving partial Classes and Structural Element Names

Perhaps the best feature of this library is that it knows how to resolve partial class names into fully qualified class 
names.

For example, you have this file:

```php
namespace My\Example;

use phpDocumentor\Reflection\Types;

class Classy
{
    /**
     * @var Types\Context
     * @see Classy::otherFunction()
     */
    public function __construct($context) {}
    
    public function otherFunction(){}
}
```

Suppose that you would want to resolve (and expand) the type in the `@var` tag and the element name in the `@see` tag.
For the resolvers to know how to expand partial names you have to provide a bit of _Context_ for them by instantiating
a new class named `\phpDocumentor\Reflection\Types\Context` with the name of the namespace and the aliases that are in 
play.

### Creating a Context

You can do this by manually creating a Context like this:

```php
$context = new \phpDocumentor\Reflect