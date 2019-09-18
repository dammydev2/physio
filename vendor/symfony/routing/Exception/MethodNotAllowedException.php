<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Routing\Matcher\Dumper;

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\RedirectableUrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 *
 * @internal
 *
 * @property RequestContext $context
 */
trait CompiledUrlMatcherTrait
{
    private $matchHost = false;
    private $staticRoutes = [];
    private $regexpList = [];
    private $dynamicRoutes = [];
    private $checkCondition;

    public function match($pathinfo)
    {
        $allow = $allowSchemes = [];
        if ($ret = $this->doMatch($pathinfo, $allow, $allowSchemes)) {
    