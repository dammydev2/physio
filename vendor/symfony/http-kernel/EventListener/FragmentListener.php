
            if ($v1 !== $v2) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets all data associated with the given key.
     *
     * Use this method only if you know what you are doing.
     *
     * @param string $key The store key
     *
     * @return array An array of data associated with the key
     */
    private function getMetadata($key)
    {
        if (!$entries = $this->load($key)) {
            return [];
        }

        return unserialize($entries);
    }

    /**
     * Purges data for the given URL.
     *
     * This method purges both the HTTP and the HTTPS version of the cache entry.
     *
     * @param string $url A URL
     *
     * @return bool true if the URL exists with either HTTP or HTTPS scheme and has been purged, false otherwise
     */
    public function purge($url)
    {
        $http = preg_replace('#^https:#', 'http:', $url);
        $https = preg_replace('#^http:#', 'https:', $url);

        $purgedHttp = $this->doPurge($http);
        $purgedHttps = $this->doPurge($https);

        return $purgedHttp || $purgedHttps;
    }

    /**
     * Purges data for the given URL.
     *
     * @param string $url A URL
     *
     * @return bool true if the URL exists and has been purged, false otherwise
     */
    private function doPurge($url)
    {
        $key = $this->getCacheKey(Request::create($url));
        if (isset($this->locks[$key])) {
            flock($this->locks[$key], LOCK_UN);
            fclose($this->locks[$key]);
            unset($this->locks[$key]);
        }

        if (file_exists($path = $this->getPath($key))) {
            unlink($path);

            return true;
        }

        return false;
    }

    /**
     * Loads data for the given key.
     *
     * @param string $key The store key
     *
     * @return string The data associated with the key
     */
    private function load($key)
    {
        $path = $this->getPath($key);

        return file_exists($path) ? file_get_contents($path) : false;
    }

    /**
     * Save data for the given key.
     *
     * @param string $key  The store key
     * @param string $data The data to store
     *
     * @return bool
     */
    private function save($key, $data)
    {
        $path = $this->getPath($key);

        if (isset($this->locks[$key])) {
            $fp = $this->locks[$key];
            @ftruncate($fp, 0);
            @fseek($fp, 0);
            $len = @fwrite($fp, $data);
            if (\strlen($data) !== $len) {
                @ftruncate($fp, 0);

                return false;
            }
        } else {
            if (!file_exists(\dirname($path)) && false === @mkdir(\dirname($path), 0777, true) && !is_dir(\dirname($path))) {
                return false;
            }

            $tmpFile = tempnam(\dirname($path), basename($path));
            if (false === $fp = @fopen($tmpFile, 'wb')) {
                @unlink($tmpFile);

                return false;
            }
            @fwrite($fp, $data);
            @fclose($fp);

            if ($data != file_get_contents($tmpFile)) {
                @unlink($tmpFile);

                return false;
            }

            if (