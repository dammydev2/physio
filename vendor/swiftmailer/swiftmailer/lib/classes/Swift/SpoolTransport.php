* be one that has been created separately.
     *
     * If $index is specified, the header will be inserted into the set at this
     * offset.
     *
     * @param int $index
     */
    public function set(Swift_Mime_Header $header, $index = 0)
    {
        $this->storeHeader($header->getFieldName(), $header, $index);
    }

    /**
     * Get the header with the given $name.
     *
     * If multiple headers match, the actual one may be specified by $index.
     * Returns NULL if none present.
     *
     * @param string $name
     * @param int    $index
     *
     * @return Swift_Mime_Header
     */
    public function get($name, $index = 0)
    {
        $name = strtolower($name);

        if (func_num_args() < 2) {
            if ($this->has($name)) {
        