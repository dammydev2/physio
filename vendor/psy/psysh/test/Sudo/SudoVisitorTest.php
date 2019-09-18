`UnsupportedOperationException`.
     *
     * @return \DateTime A PHP DateTime representation of the date
     * @throws UnsupportedOperationException If this UUID is not a version 1 UUID
     * @throws \Ramsey\Uuid\Exception\UnsatisfiedDependencyException if called in a 32-bit system and
     *     `Moontoast\Math\BigNumber` is not present
     */
    public function getDateTime();

    /**
     * Returns the integer value of the UUID, converted to an appropriate number
     * representation.
     *
     * @return mixed Converted representation of the unsigned 128-bit integer value
     * @throws \Ramsey\Uuid\Exception\UnsatisfiedDependencyException if `Moontoast\Math\BigNumber` is not present
     */
    public function getInteger();

    /**
     * Returns the least significant 64 bits of this UUID's 128 bit value.
     *
     * @return string Hexadecimal value of least significant bits
     */
    public function getLeastSignificantBitsHex();

    /**
     * Returns the most significant 64 bits of this UUID's 128 bit value.
     *
     * @return string Hexadecimal value of most significant bits
     */
    public function getMostSignificantBitsHex();

    /**
     * Returns the node value associated with this UUID
     *
     * For UUID version 1, the node field consists of an IEEE 802 MAC
     * address, usually the host address. For systems with multiple IEEE
     * 802 addresses, any available one can be used. The lowest addressed
     * octet (octet number 10) contains the global/local bit and the
     * unicast/multicast bit, and is the first octet of the address
     * transmitted on an 802.3 LAN.
     *
     * For systems with no IEEE address, a randomly or pseudo-randomly
     * generated value may be used; see RFC 4122, Section 4.5. The
     * multicast bit must be set in such addresses, in order that they
     * will never conflict with addresses obtained from network cards.
     *
     * For UUID version 3 or 5, the node field is a 48-bit value constructed
     * from a name as described in RFC 4122, Section 4.3.
     *
     * For UUID version 4, the node field is a randomly or pseudo-randomly
     * generated 48-bit value as described in RFC 4122, Section 4.4.
     *
     * @return string Hexadecimal value of node
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.6
     */
    public function getNodeHex();

    /**
     * Returns the high field of the timestamp multiplexed with the version
     * number (bits 49-64 of the UUID).
     *
     * @return string Hexadecimal value of time_hi_and_version
     */
    public function getTimeHiAndVersionHex();

    /**
     * Returns the low field of the timestamp (the first 32 bits of the UUID).
     *
     * @return string Hexadecimal value of time_low
     */
    public function getTimeLowHex();

    /**
     * Returns the middle field of the timestamp (bits 33-48 of the UUID).
     *
     * @return string Hexadecimal value of time_mid
     */
    public function getTimeMidHex();

    /**
     * Returns the timestamp value associated with this UUID.
     *
     * The 60 bit timestamp value is constructed from the time_low,
     * time_mid, and time_hi fields of this UUID. The resulting
     * timestamp is measured in 100-nanosecond units since midnight,
     * October 15, 1582 UTC.
     *
     * The timestamp value is only meaningful in a time-based UUID, which
     * has version type 1. If this UUID is not a time-based UUID then
     * this method throws UnsupportedOperationException.
     *
     * @return string Hexadecimal va