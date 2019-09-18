(array) $addresses);
        }

        return $this;
    }

    /**
     * Get the To addresses of this message.
     *
     * @return array
     */
    public function getTo()
    {
        return $this->getHeaderFieldModel('To');
    }

    /**
     * Add a Cc: address to this message.
     *
     * If $name is passed this name will be associated with the address.
     *
     * @param string $address
     * @param string $name    optional
     *
     * @return $this
     */
    public function addCc($address, $name = null)
    {
        $current = $this->getCc();
        $current[$address] = $name;

        return $this->setCc($current);
    }

    /**
     * Set the Cc addresses of this message.
     *
     * If $name is passed and the first parameter is a string, this name will be
     * associated with the address.
     *
     * @param mixed  $addresses
     * @param string $name      optional
     *
     * @return $this
     */
    public function setCc($addresses, $name = null)
    {
        if (!is_array($addresses) && isset($name)) {
            $addresses = [$addresses => $name];
    