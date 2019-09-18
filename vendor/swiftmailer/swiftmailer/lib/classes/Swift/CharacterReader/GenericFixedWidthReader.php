   * Set the model data for $field.
     */
    protected function setHeaderFieldModel($field, $model)
    {
        if ($this->headers->has($field)) {
            $this->headers->get($field)->setFieldBodyModel($model);

            return true;
        }

        return false;
    }

    /**
     * Get the parameter value of $parameter on $field header.
     */
    protected function getHeaderParameter($field, $parameter)
    {
        if ($this->headers->has($field)) {
            return $this->headers->get($field)->getParameter($parameter);
        }
    }

    /**
     * Set the parameter value of $parameter on $field header.
     */
    protected function setHeaderParameter($field, $parameter, $value)
    {
        if ($this->headers->has($field)) {
            $this->headers->get($field)->setParameter($parameter, $value);

            return true;
        }

        return false;
    }

    /**
     * Re-evaluate what content type and encoding should be used on this entity.
     */
    protected function fixHeaders()
    {
        if (count($this->immediateChildren)) {
            $this->setHeaderParameter('Content-Type', 'boundary',
                $this->getBoundary()
                );
            $this->headers->remove('Content-Transfer-Encoding');
        } else {
            $this->setHeaderParameter('Content-Type', 'boundary', null);
            $this->setEncoding($this->encoder->getName());
        }
    }

    /**
     * Get the KeyCache used in this entity.
     *
     * @return Swift_KeyCache
     */
    protected function getCache()
    {
        return $this->cache;
    }

    /**
     * Get the ID generator.
     *
     * @return Swift_IdGenerator
     */
    protected function getIdGenerator()
    {
        return $this->idGenerator;
    }

    /**
     * Empty the KeyCache for this entity.
     */
    protected function clearCache()
    {
        $this->cache->clearKey($this->cacheKey, 'body');
    }

    private function readStream(Swift_OutputByteStream $os)
    {
        $string = '';
        while (false !== $bytes = $os->read(8192)) {
            $string .= $bytes;
        }

        $os->setReadPointer(0);

        return $string;
    }

    private function setEncoding($encoding)
    {
        if (!$this->setHeaderFieldModel('Content-Transfer-Encoding', $encoding)) {
            $this->headers-