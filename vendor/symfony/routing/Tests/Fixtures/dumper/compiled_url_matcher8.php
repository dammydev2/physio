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
        $this->staticRoutes = [
            '/a/11' => [[['_route' => 'a_first'], null, null, null, false, false, null]],
            '/a/22' => [[['_route' => 'a_second'], null, null, null, false, false, null]],
            '/a/333' => [[['_route' => 'a_third'], null, null, null, false, false, null]],
            '/a/44' => [[['_route' => 'a_fourth'], 