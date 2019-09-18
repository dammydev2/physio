
     */
    public function exportToByteStream($nsKey, $itemKey, Swift_InputByteStream $is)
    {
        $this->prepareCache($nsKey);
        $is->write($this->getString($nsKey, $itemKey));
    }

    /**
     * Check if the given $itemKey exists in the namespace $nsKey.
     *
     * @param string $nsKey
     * @param string $itemKey
     *
     * @return bool
     */
    public function hasKey($nsKey, $itemKey)
    {
        $this->prepareCache($nsKey);

        return array_key_exists($itemKey, $this->contents[$nsKey]);
    }

    /**
     * Clear data for $itemKey in the namespace $nsKey if it exists.
     *
     * @param string $nsKey
     * @param string $itemKey
     */
    public function clearKey($nsKey, $itemKey)