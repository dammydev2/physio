CHANGELOG
=========

4.2.0
-----

 * added fallback to cultureless locale for internationalized routes

4.0.0
-----

 * dropped support for using UTF-8 route patterns without using the `utf8` option
 * dropped support for using UTF-8 route requirements without using the `utf8` option

3.4.0
-----

 * Added `NoConfigurationException`.
 * Added the possibility to define a prefix for all routes of a controller via @Route(name="prefix_")
 * Added support for prioritized routing loaders.
 * Add matched and default parameters to redirect responses
 * Added support for a `controller` keyword for configuring route controllers in YAML and XML configurations.

3.3.0
-----

  * [DEPRECATION] Class parameters have been deprecated and will be removed in 4.0.
    * router.options.generator_class
    * router.opti