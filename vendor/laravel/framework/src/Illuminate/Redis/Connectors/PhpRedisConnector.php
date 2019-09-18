<?php

namespace Illuminate\Routing;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\InteractsWithTime;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Routing\UrlGenerator as UrlGeneratorContract;

class UrlGenerator implements UrlGeneratorContract
{
    use InteractsWithTime, Macroable;

    /**
     * The route collection.
     *
     * @var \Illuminate\Routing\RouteCollection
     */
    protected $routes;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The asset root URL.
     *
     * @var string
     */
    protected $assetRoot;

    /**
     * The forced URL root.
     *
     * @var string
     */
    protected $forcedRoot;

    /**
     * The forced scheme for URLs.
     *
     * @var string
     */
    protected $forceScheme;

    /**
     * A cached copy of the URL root for the current request.
     *
     * @var string|null
     */
    protected $cachedRoot;

    /**
     * A cached copy of the URL scheme for the current request.
     *
     * @var string|null
     */
    protected $cachedScheme;

    /**
     * The root namespace being applied to controller actions.
     *
     * @var string
     */
    protected $rootNamespace;

    /**
     * The session resolver callable.
     *
     * @var callable
     */
    protected $sessionResolver;

    /**
     * The encryption key resolver callable.
     *
     * @var callable
     */
    protected $keyResolver;

    /**
     * The callback to use to format hosts.
     *
     * @var \Closure
     */
    protected $formatHostUsing;

    /**
     * The callback to use to format paths.
     *
     * @var \Closure
     */
    protected $formatPathUsing;

    /**
     * The route URL generator instance.
     *
     * @var \Illuminate\Routing\RouteUrlGenerator|null
     */
    protected $routeGenerator;

    /**
     * Create a new URL Generator instance.
     *
     * @param  \Illuminate\Routing\RouteCollection  $routes
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $assetRoot
     * @return void
     */
    public function __construct(RouteCollection $routes, Request $request, $assetRoot = null)
    {
        $this->routes = $routes;
        $this->assetRoot = $assetRoot;

        $this->setRequest($request);
    }

    /**
     * Get the full URL for the current request.
     *
     * @return string
     */
    public function full()
    {
        return $this->request->fullUrl();
    }

    /**
     * Get the current URL for the request.
     *
     * @return string
     */
    public function current()
    {
        return $this->to($this->request->getPathInfo());
    }

    /**
     * Get the URL for the previous request.
     *
     * @param  mixed  $fallback
     * @return string
     */
    public function previous($fallback = false)
    {
        $referrer = $this->request->headers->get('referer');

        $url = $referrer ? $this->to($referrer) : $this->getPreviousUrlFromSession();

        if ($url) {
            return $url;
        } elseif ($fallback) {
            return $this->to($fallback);
        }

        return $this->to('/');
    }

    /**
     * Get the previous URL from the session if possible.
     *
     * @return string|null
     */
    protected function getPreviousUrlFromSession()
    {
        $session = $this->getSession();

        return $session ? $session->previousUrl() : null;
    }

    /**
     * Generate an absolute URL to the given path.
     *
     * @param  string  $path
     * @param  mixed  $extra
