expiration time to a Unix timestamp
        if ($expire instanceof \DateTimeInterface) {
            $expire = $expire->format('U');
        } elseif (!is_numeric($expire)) {
            $expire = strtotime($expire);

            if (false === $expire) {
                throw new \InvalidArgumentException('The cookie expiration time is not valid.');
            }
        }

        $this->name = $name;
        $this->value = $value;
        $this->domain = $domain;
        $this->expire = 0 < $expire ? (int) $expire : 0;
        $this->path = empty($path) ? '/' : $path;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
        $this->raw = $raw;

        if ('' === $sameSite) {
            $sameSite = null;
        } elseif (null !== $sameSite) {
            $sameSite = strtolower($sameSite);
        }

        if (!\in_array($sameSite, [self::SAMESITE_LAX, self::SAMESITE_STRICT, null], true)) {
            throw new \InvalidArgumentException('The "sameSite" parameter value is not valid.');
        }

        $this->sameSite = $sameSite;
    }

    /**
     * Returns the cookie as a string.
     *
     * @return string The cookie
     */
    public function __toString()
    {
        $str = ($this->isRaw() ? $this->getName() : urlencode($this->getName())).'=';

        if ('' === (string) $this->getValue()) {
            $str .= 'deleted; expires='.gmdate('D, d-M-Y H:i:s T', time() - 31536001).'; Max-Ag