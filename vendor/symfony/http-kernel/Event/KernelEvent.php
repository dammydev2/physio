<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This code is partially based on the Rack-Cache library by Ryan Tomayko,
 * which is released under the MIT license.
 * (based on commit 02d2b48d75bcb63cf1c0c7149c077ad256542801)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpKernel\HttpCache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;

/**
 * Cache provides HTTP caching.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class HttpCache implements HttpKernelInterface, TerminableInterface
{
    private $kernel;
    private $store;
    private $request;
    private $surrogate;
    private $surrogateCacheStrategy;
    private $options = [];
    private $traces = [];

    /**
     * Constructor.
     *
     * The available options are:
     *
     *   * debug:                 If true, the traces are added as a HTTP header to ease debugging
     *
     *   * default_ttl            The number of seconds that a cache entry should be considered
     *                            fresh when no explicit freshness information is provided in
     *                            a response. Explicit Cache-Control or Expires headers
     *                            override this value. (default: 0)
     *
     *   * private_headers        Set of request headers that trigger "private" cache-control behavior
     *                            on responses that don't explicitly state whether the response is
     *                            public or private via a Cache-Control directive. (default: Authorization and Cookie)
     *
     *   * allow_reload           Specifies whether the client can force a cache reload by including a
     *                            Cache-Control "no-cache" directive in the request. Set it to ``true``
     *                            for compliance with RFC 2616. (default: false)
     *
     *   * allow_revalidate       Specifies whether the 