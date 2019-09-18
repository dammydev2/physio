 has changed.
     *
     * @param string $charset
     */
    public function charsetChanged($charset)
    {
        $this->notifyCharsetChanged($charset);
    }

    /**
     * Receive notification that the encoder of this entity or a parent entity
     * has changed.
     */
    public function encoderChanged(Swift_Mime_ContentEncoder $encoder)
    {
        $this->notifyEncoderChanged($encoder);
    }

    /**
     * Get this entire entity as a string.
     *
     * @return string
     */
    public function toString()
    {
        $string = $this->headers->toString();
        $string .= $this->bodyToString();

        return $string;
    }

    /**
     * Get this entire entity as a string.
     *
     * @return string
     */
    protected function bodyToString()
    {
        $string = '';

        if (isset($this->body) && empty($this->immediateChildren)) {
            if ($this->cache->hasKey($this->cacheKey, 'body')) {
                $body = $this->cache->getString($this->c