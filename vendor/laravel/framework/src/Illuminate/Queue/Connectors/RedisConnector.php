<?php

namespace Illuminate\Routing;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Session\Store as SessionStore;

class Redirector
{
    use Macroable;

    /**
     * The URL generator instance.
     *
     * @var \Illuminate\Routing\UrlGenerator
     */
    protected $generator;

    /**
     * The session store instance.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Create a new Redirector instance.
     *
     * @param  \Illuminate\Routing\UrlGenerator  $generator
     * @return void
     */
    public function __construct(UrlGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Create a new redirect response to the "home" route.
     *
     * @param  int  $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function home($status = 302)
    {
        return $this->to($this->generator->route('home'), $status);
    }

    /**
     * Create a new redirect response to the previous location.
     *
     * @param  int    $status
     * @param  array  $headers
     * @param  mixed  $fallback
     * @return \Il