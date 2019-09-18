     * Get the sender of this message.
     *
     * @return string
     */
    public function getSender()
    {
        return $this->getHeaderFieldModel('Sender');
    }

    /**
     * Add a From: address to this message.
     *
     * If $name is passed this name will be associated with the address.
     *
     * @param string $address
     * @param string $name    optional
     *
     * @return $this
     */
    public function addFrom($address, $name = null)
    {
        $current = $this->getFrom();
        $current[$address] = $name;

        return $this->setFrom($current);
    }

    /**
     * Set the from address of this message.
     *
     * You may pass an array of addresses if this message is from multiple people.
     *
     * If $name is passed and the first parameter is a string, this name will be
     * associated with the address.
     *
     * @param string|array $addresses
     * @param string       $name      optional
     *
     * @return $this
     */
    public function setFrom($addresses, $name = null)
    {
        if (!is_array($addresses) && isset($name)) {
            $addresses = [$addresses => $name];
        }

        if (!$this->setHeaderFieldModel('From', (array) $addresses)) {
            $this->getHeaders()->addMailboxHeader('From', (array) $addresses);
        }

        return $this;
    }

    /**
     * Get the from address of this message.
     *
     * @return mixed
     */
    public function getFrom()
    {
        return $this->getHeaderFieldModel('From');
    }

    /**
     * Add a Reply-To: address to th