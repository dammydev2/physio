ath.config').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('cookie')) {
    /**
     * Create a new cookie instance.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  int  $minutes
     * @param  string  $path
     * @param  string  $domain
     * @param  bool  $secure
     * @param  bool  $httpOnly
     * @param  bool  $raw
     * @param  string|null  $sameSite
     * @return \Illuminate\Cookie\CookieJar|\Symfony\Component\HttpFoundation\Cookie
     */
    function cookie($name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = false, $httpOnly = true, $raw = false, $sameSite = null)
    {
        $cookie = app(CookieFactory::class);

        if (is_null($name)) {
            return $cookie;
        }

        return $cookie->make($name, $value, $minutes, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
    }
}

if (! function_exists('csrf_field')) {
    /**
     * Generate a CSRF token form field.
     *
     * @return \Illuminate\Support\HtmlString
     */
    function csrf_field()
    {
        return new HtmlString('<input type="hidden" name="_token" value="'.csrf_token().'">');
    }
}

if (! function_exists('csrf_token')) {
    /**
     * Get the CSRF token value.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    function csrf_token()
    {
        $session = app('session');

        if (isset($session)) {
            return $session->token();
        }

        throw new RuntimeException('Application session store not set.');
    }
}

if (! function_exists('database_path')) {
    /**
     * Get the database path.
     *
     * @param  string  $path
     * @return string
     */
    function database_path($path = '')
    {
        return app()->databasePath($path);
    }
}

if (! function_exists('decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param  string  $value
     * @param  bool   $unserialize
     * @return mixed
     */
    function decrypt($value, $unserialize = true)
    {
        return app('encrypter')->decrypt($value, $unserialize);
    }
}

if (! function_exists('dispatch')) {
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param  mixed  $job
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    function dispatch($job)
    {
        if ($job instanceof Closure) {
            $job = new CallQueuedClosure(new