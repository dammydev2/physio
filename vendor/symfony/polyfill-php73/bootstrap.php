subCollection);
   $subCollection->add('foo', new Route('/foo'));
   ```

   After:

   ```php
   $rootCollection = new RouteCollection();
   $subCollection = new RouteCollection();
   $subCollection->add('foo', new Route('/foo'));
   $rootCollection->addCollection($subCollection);
   ```

   Also one must call `addCollection` from the bottom to the top hierarchy.
   So the correct sequence is the following (and not the reverse):

   ```php
   $childCollection->addCollection($grandchildCollection);
   $rootCollection->addCollection($childCollection);
   ```

 * [DEPRECATION] The methods `RouteCollection::getParent()` and `RouteCollection::getRoot()`
   have been deprecated and will be removed in Symfony 2.3.
 * [BC BREAK] Misusing the `RouteCollection::addPrefix` method to add defaults, requirements
   or options without adding a prefix is not supported anymore. So if you called `addPrefix`
   with an empty prefix or `/` only (both have no relevance), like
   `addPre