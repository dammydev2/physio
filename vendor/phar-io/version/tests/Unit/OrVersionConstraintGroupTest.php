TypeResolver and FqsenResolver
==============================

The specification on types in DocBlocks (PSR-5) describes various keywords and special constructs
but also how to statically resolve the partial name of a Class into a Fully Qualified Class Name (FQCN).

PSR-5 also introduces an additional way to describe deeper elements than Classes, Interfaces and Traits 
called the Fully Qualified Structural Element Name (FQSEN). Using this it is possible to refer to methods,
properties and class constants but also functions and global constants.

This package provides two Resolvers that are capable of 

1. Returning a series of Value Object for given expression while resolving any partial class names, and 
2. Returning an FQSEN object after resolving any partial Structural Element Names into Fully Qualified Structural 
   Element names.

## Installing

The easiest way to install this library is with [Composer](https://getcomposer.org) using the following command:

    $ composer require phpdocumentor/type-resolver

## Examples

Ready to dive in and don't want to read through all that text below? Just consult the [examples](examples) folder and
check which type of action that your want to accomplish.

## On Types and Element Names

This component can be used in one of two ways
 
1. To resolve a Type or
2. To resolve a Fully Qualified Structural Element Name
 
The big difference between these two is in the number of things it can resolve. 

The TypeResolver can resolve:

- a php primitive or pseudo-primitive such as a string or void (`@var string` or `@return void`).
- a composite such as an array of string (`@var string[]`).
- a compound such as a string or integer (`@var string|integer`).
- an object or interface such as the TypeResolver class (`@var TypeResolver` 
  or `@var \phpDocumentor\Reflection\TypeResolver`)

  > please note that if you want to pass partial class names that additional steps are necessary, see the 
  > chapter `Resolving partial classes and FQSENs` for more information.

Where the FqsenResolver can resolve:

- Constant expressions (i.e. `@see \MyNamespace\MY_CONSTANT`)
- Function expressions (i.e. `@see \MyNamespace\myFunction()`)
- Class expressions (i.e. `@see \MyNamespace\MyClass`)
- Interface expressions (i.e. `@see \