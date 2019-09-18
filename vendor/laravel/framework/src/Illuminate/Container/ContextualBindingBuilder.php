<?php

namespace Illuminate\Cookie\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;

class EncryptCookies
{
    /**
     * The encrypter instance.
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;

    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [];

    /**
     * Indicates if cookies should be serialized.
     *
     * @var bool
     */
    protected static $serialize = false;

    /**
     * Create a new CookieGuard instance.
     *
     * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
     * @return void
     */
    public function __construct(EncrypterContract $encrypter)
    {
        $this->encrypter = $encrypter;
    }

    /**
     * Disable encryption for the given cookie name(s).
     *
     * @param  string|array  $name
     * @return void
     */
    public function disableFor($name)
    {
        $this->except = array_merge($this->except, (array) $name);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        return $this->encrypt($next($this->decrypt($request)));
    }

    /**
    