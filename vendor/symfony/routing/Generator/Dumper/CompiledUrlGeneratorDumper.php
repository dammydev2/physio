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
trait PhpMatcherTrait
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
            return $ret;
        }
        if ($allow) {
            throw new MethodNotAllowedException(array_keys($allow));
        }
        if (!$this instanceof RedirectableUrlMatcherInterface) {
            throw new ResourceNotFoundException();
        }
        if (!\in_array($this->context->getMethod(), ['HEAD', 'GET'], true)) {
            // no-op
        } elseif ($allowSchemes) {
            redirect_scheme:
            $scheme = $this->context->getScheme();
            $this->context->setScheme(key($allowSchemes));
            try {
                if ($ret = $this->doMatch($pathinfo)) {
                    return $this->redirect($pathinfo, $ret['_route'], $this->context->getScheme()) + $ret;
                }
            } finally {
                $this->context->setScheme($scheme);
            }
        } elseif ('/' !== $trimmedPathinfo = rtri