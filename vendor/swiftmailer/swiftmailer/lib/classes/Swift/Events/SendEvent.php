e_Headers_MailboxHeader('From',
     *  array('chris@swiftmailer.org' => 'Chris Corbyn',
     *  'mark@swiftmailer.org' => 'Mark Corbyn')
     *  );
     * print_r($header->getNameAddresses());
     * // array (
     * // chris@swiftmailer.org => Chris Corbyn,
     * // mark@swiftmailer.org => Mark Corbyn
     * // )
     * ?>
     * </code>
     *
     * @see getAddresses()
     * @see getNameAddressStrings()
     *
     * @return string[]
     */
    public function getNameAddresses()
    {
        return $this->mailboxes;
    }

    /**
     * Makes this Header represent a list of plain email addresses with no names.
     *
     * Example:
     * <code>
     * <?php
     * //Sets three email addresses as the Header data
     * $header->setAddresses(
     *  array('one@domain.tld', 'two@domain.tld', 'three@domain.tld')
     *  );
     * ?>
     * </code>
     *
     * @see setNameAddresses()
     * @see setValue()
     *
     * @param string[] $addresses
     *
     * @throws Swift_RfcComplianceException
     */
    public function setAddresses($addresses)
    {
        $this->setNameAddresses(array_values((array) $addresses));
    }

    /**
     * Get all email addresses in this Header.
     *
     * @see getNameAddresses()
     *
     * @return string[]
     */
    public function getAddresses()
    {
        return array_keys($this->mailboxes);
    }

    /**
     * Remove one or more addresses from this Header.
     *
     * @param string|string[] $addresses
     */
    public function removeAddresses($addresses)
    {
        $this->setCachedValue(null);
        foreach ((array) $addresses as $address) {
            unset($this->mailboxes[$address]);
        }
    }

    /**
     * Get the string value of the body in this Header.
     *
     * This is not necessarily RFC 2822 compliant since folding white space will
     * not be added at this stage (see {@link toString()} for that).
     *
     * @see toString()
     *
     * @throws Swift_RfcComplianceException
     *
     * @return string
     */
    public function getFieldBody()
    {
        // Compute the string value of the header only if needed
        if (null === $this->getCachedValue()) {
            $this->setCachedValue($this->createMailboxListString($this->mailboxes));
        }

        return $this->getCachedValue();
    }

    /**
     * Normalizes a user-input list of mailboxes into consistent key=>value pairs.
     *
     * @param string[] $mailboxes
     *
     * @return string[]
     */
    protected function normalizeMailboxes(array $mailboxes)
    {
        $actualMailboxes = [];

        foreach ($mailboxes as $key => $valu