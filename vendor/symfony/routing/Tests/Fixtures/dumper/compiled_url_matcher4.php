<?php

use Symfony\Component\Routing\Matcher\Dumper\PhpMatcherTrait;
use Symfony\Component\Routing\RequestContext;

/**
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class ProjectUrlMatcher extends Symfony\Component\Routing\Tests\Fixtures\RedirectableUrlMatcher
{
    use PhpMatcherTrait;

    public function __construct(RequestContext $context)
    {
        $this->context = $context;
        $this->matchHost = true;
        $this->staticRoutes = [
            '/test/baz' => [[['_route' => 'baz'], null, null, null, false, false, null]],
            '/test/baz.html' => [[['_route' => 'baz2'], null, null, null, false, false, null]],
            '/test/baz3' => [[['_route' => 'baz3'], null, null, null, true, false, null]],
            '/foofoo' => [[['_route' => 'foofoo', 'def' => 'test'], null, null, null, false, false, null]],
            '/spa ce' => [[['_route' => 'space'], null, null, null, false, false, null]],
            '/m