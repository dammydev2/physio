immediateChildren as $child) {
            $child->encoderChanged($encoder);
        }
    }

    private function notifyCharsetChanged($charset)
    {
        $this->encoder->charsetChanged($charset);
        $this->headers->charsetChanged($charset);
        foreach ($this->immediateChildren as $child) {
            $child->charsetChanged($charset);
        }
    }

    private function sortChildren()
    {
        $shouldSort = false;
        foreach ($this->immediateChildren as $child) {
            // NOTE: This include alternative parts moved into a related part
            if (self::LEVEL_ALTERNATIVE == $child->getNestingLevel()) {
                $shouldSort = true;
                break;
            }
        }

        // Sort in order of preference, if there is one
        if ($shouldSort) {
            // Group the messages by order of preference
            $sorted = [];
            foreach ($this->immediateChildren as $child) {
                $type = $child->getContentType();
                $level = array_key_exists($type, $this->alternativePartOrder) ? $this->alternativePartOrder[$type] : max($this->alternativePartOrder) + 1;

                if (empty($sorted[$level])) {
                    $sorted[$level] = [];
                }

                $sorted[$level][] = $child;
            }

            ksort($sorted);

            $this->immediateChildren = array_reduce($sorted, 'array_merge', []);
        }
    }

    /**
     * Empties it's own contents from the cache.
     */
    public function __destruct()
    {
        if ($this->cache instanceof Swift_KeyCache) {
            $this->cache->clearAll($this->cacheKey);
        }
    }

    /**
     * Make a deep copy of object.
     */
    public function __clone()
    {
        $this->headers = clone $this->headers;
        $this->encoder = clone $this->encoder;
        $this->cacheKey = bin2hex(random_bytes(16)); // set 32 hex values
        $children = [];
        foreach