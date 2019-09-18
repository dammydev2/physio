imestamp is measured in 100-nanosecond units since midnight,
     * October 15, 1582 UTC.
     *
     * The timestamp value is only meaningful in a time-based UUID, which
     * has version type 1. If this UUID is not a time-based UUID then
     * this method throws UnsupportedOperationException.
     *
     * @return int Unsigned 60-bit integer value of the timestamp
     * @throws UnsupportedOperationException If this UUID is not a version 1 UUID
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.4
     */
    public function getTimestamp()
    {
        if ($this->getVersion() != 1) {
            throw new UnsupportedOperationException('Not a time-based UUID');
        }

        return hexdec($this->getTimestampHex());
    }

    /**
     * @inheritdoc
     */
    public function getTimestampHex()
    {
        if ($this->getVersion() != 1) {
            thro