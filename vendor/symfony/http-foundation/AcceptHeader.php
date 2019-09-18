eritdoc}
     */
    public function getCacheControlDirective($key)
    {
        return \array_key_exists($key, $this->computedCacheControl) ? $this->computedCacheControl[$key] : null;
    }

    public function setCookie(Cookie $cookie)
    {
        $this->cookies[$cookie->getDomain()][$cookie->getPath()][$cookie->getName()] = $cookie;
        $this->headerNames['set-cookie'] = 'Set-Cookie';
    }

    /**
     * Removes a cookie from the array, but does not unset it in the browser.
     *
     * @param string $name
     * @param string $path
     * @param string $domain
     */
    public function removeCookie($name, $path = '/', $domain = null)
    {
        if (null === $path) {
            $path = '/';
        }

        unset($this->cookies[$domain][$path][$name]);

        if (empty($this->cookies[$domain][$path])) {
            unset($this->cookies[$domain][$path]);

            if (empty($this->cookies[$domain])) {
                unset($this->cookies[$domain]);
            }
        }

        if (empty($this->cookies)) {
            unset($this->headerNames['set-cookie']);
        }
    }

    /**
     * Returns an array with all cookies.
     *
     * @param string $format
     *
     * @return Cookie[]
     *
     * @throws \InvalidArgumentException When the $format is invalid
     */
    public function getCookies($format = self::COOKIES_FLAT)
    {
        if (!\in_array($format, [self::COOKIES_FLAT, self::COOKIES_ARRAY])) {
            throw new \InvalidArgumentException(sprintf('Format "%s" invalid (%s).', $format, implode(', ', [self::COOKIES_FLAT, self::COOKIES_ARRAY])));
        }

        if (self::COOKIES_ARRAY === $format) {
            return $this->cookies;
        }

        $flattenedCookies = [];
        foreach ($this->cookies as $path) {
            foreach ($path as $cookies) {
                foreach ($cookies as $cookie) {
                    $flattenedCookies[] = $cookie;
                }
            }
        }

        return $flattenedCookies;
    }

    /**
     * Clears a cookie in the browser.
     *
     * @param string $name
     * @param string $path
     * @param string $domain
     * @param bool   $secure
     * @param bool   $httpOnly
     */
    public function clearCookie($name, $path = '/', $domain = null, $secure = false, $httpOnly = true)
    {
        $this->setCookie(new Cookie($name, null, 1, $path, $domain, $secure, $httpOnly, false, null));
    }

    /**
     * @see HeaderUtils::makeDisposition()
     */
    public function makeDisposition($disposition, $filename, $filenameFallback = '')
    {
        return HeaderUtils::makeDisposition((string) $disposition, (string) $filename, (string) $filenameFallback);
    }

    /**
     * Returns the calculated value of the cache-control header.
     *
     * This considers several other headers and calculates or modifies the
     * cache-control header to a sensible, conservative value.
     *
     * @return string
     */
    protected function computeCacheControlValue()
    {
        if (!$this->cacheControl && !$this->has('ETag') && !$this->has('Last-Modified') && !$this->has('Expires')) {
            return 'no-cache, private';
        }

        if (!$this->cacheControl) {
            // conservative by default
            return 'private, must-revalidate';
        }

        $header = $this->getCacheControlHeader();
        if (isset($this->cacheControl['public']) || isset($this->cacheControl['private'])) {
            return $header;
        }

        // public if s-maxage is defined, private otherwise
        if (!isset($this->cacheControl['s-maxage'])) {
            return $header.',