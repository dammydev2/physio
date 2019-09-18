r->fromHex($this->getHex());
    }

    /**
     * Returns the least significant 64 bits of this UUID's 128 bit value.
     *
     * @return mixed Converted representation of the unsigned 64-bit integer value
     * @throws \Ramsey\Uuid\Exception\UnsatisfiedDependencyException if `Moontoast\Math\BigNumber` is not present
     */
    public function getLeastSignificantBits()
    {
        return $this->converter->fromHex($this->getLeastSignificantBitsHex());
    }

    public function getLeastSignificantBitsHex()
    {
        return sprintf(
            '%02s%02s%012s',
            $this->fields['clock_seq_hi_and_reserved'],
            $this->fields['clock_seq_low'],
            $this->fields['node']
        );
    }

    /**
     * Returns the most significant 64 bits of this UUID's 128 bit value.
     *
     * @return mixed Converted representation of the unsigned 64-bit integer value
     * @throws \Ramsey\Uuid\Exception\UnsatisfiedDependencyException if `Moontoast\Math\BigNumber` is not present
     */
    public function getMostSignificantBits()
    {
        return $this->converter->fromHex($this->getMostSignificantBitsHex());
    }

    public function getMostSignificantBitsHex()
    {
        return sprintf(
            '%08s%04s%04s',
            $this->fields['time_low'],
            $this->fields['time_mid'],
            $this->fields['time_hi_and_version']
        );
    }

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
     * @return int Unsigned 48-bit integer value of node
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.6
     */
   