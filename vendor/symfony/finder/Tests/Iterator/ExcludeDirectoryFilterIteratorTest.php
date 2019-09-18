 $requestUri[0]) {
            $requestUri = '/'.$requestUri;
        }

        if (null === ($baseUrl = $this->getBaseUrl())) {
            return $requestUri;
        }

        $pathInfo = substr($requestUri, \strlen($baseUrl));
        if (false === $pathInfo || '' === $pathInfo) {
            // If substr() returns false then PATH_INFO is set to an empty string
            return '/';
        }

        return (string) $pathInfo;
    }

    /**
     * Initializes HTTP request formats.
     */
    protected static function initializeFormats()
    {
        static::$formats = [
            'html' => ['text/html', 'application/xhtml+xml'],
            'txt' => ['text/plain'],
            'js' => ['application/javascript', 'application/x-javascript', 'text/javascript'],
            'css' => ['text/css'],
            'json' => ['application/json', 'application/x-json'],
            'jsonld' => ['application/ld+json'],
            'xml' => ['text/xml', 'application/xml', 'application/x-xml'],
            'rdf' => ['application/rdf+xml'],
            'atom' => ['application/atom+xml'],
            'rss' => ['application/rss+xml'],
            'form' => ['application/x-www-form-urlencoded'],
        ];
    }

    private function setPhpDefaultLocale(string $locale)
    {
        // if either the class Locale doesn't exist, or an exception is thrown when
        // setting the default locale, the intl module is not installed, and
        // the call can be ignored:
        try {
            if (class_exists('Locale', false)) {
                \Locale::setDefault($locale);
            }
        } catch (\Exception $e) {
        }
    }

    /*
     * Returns the prefix as encoded in the string when the string starts with
     * the given prefix, false otherwise.
     *
     * @return string|false The prefix as it is encoded in $string, or false
     */
    private function getUrlencodedPrefix(string $string, string $prefix)
    {
        if (0 !== strpos(rawurldecode($string), $prefix)) {
            return false;
        }

        $len = \strlen($prefix);

        if (preg_match(sprintf('#^(%%[[:xdigit:]]{2}|.){%d}#', $len), $string, $match)) {
            return $match[0];
        }

        return false;
    }

    private static function createRequestFromFactory(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        if (self::$requestFactory) {
            $request = (self::$requestFactory)($query, $request, $attributes, $cookies, $files, $server, $content);

            if (!$request instanceof self) {
                throw new \LogicException('The Request fac