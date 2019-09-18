          '%[1-5]'
            );

        return $priority ?? 3;
    }

    /**
     * Ask for a delivery receipt from the recipient to be sent to $addresses.
     *
     * @param array $addresses
     *
     * @return $this
     */
    public function setReadReceiptTo($addresses)
    {
        if (!$this->setHeaderFieldModel('Disposition-Notification-To', $addresses)) {
            $this->getHeaders()
                ->addMailboxHeader('Disposition-Notification-To', $addresses);
        }

        return $this;
    }

    /**
     * Get the addresses to which a read-receipt will be sent.
     *
     * @return string
     */
    public function getReadReceiptTo()
    {
        return $this->getHeaderFieldModel('Disposition-Notification-To');
    }

    /**
     * Attach a {@link Swift_Mime_SimpleMimeEntity} such as an Attachment or MimePart.
     *
     * @return $this
     */
    public function attach(Swift_Mime_SimpleMimeEntity $entity)
    {
        $this->setChildren(array_merge($this->getChildren(), [$entity]));

        return $this;
    }

    /**
     * Remove an already attached entity.
     *
     * @return $this
     */
    public function detach(Swift_Mime_SimpleMimeEntity $entity)
    {
        $newChildren = [];
        foreach ($this->getChildren() as $child) {
            if ($entity !== $child) {
                $newChildren[] = $child;
            }
        }
        $this->setChildren($newChildren);

        return $this;
    }

    /**
     * Attach a {@link Swift_Mime_SimpleMimeEntity} and return it's CID source.
     *
     * This method should be used when embedding images or other data in a message.
     *
     * @return string
     */
    public function embed(Swift_Mime_SimpleMimeEntity $entity)
    {
        $this->attach($entity);

        return 'cid:'.$entity->getId();
    }

    /**
     * Get this message as a complete string.
     *
     * @return string
     */
    public function toString()
    {
        if (count($children = $this->getChildren()) > 0 && '' != $this->getBody()) {
            $this->setChildren(array_merge([$this->becomeMimePart()], $children));
            $string = parent::toString();
            $this->setChildren($children);
        } else {
            $string = parent::toString();
        }

        return $string;
    }

    /**
     * Returns a string representation of this object.
     *
     * @see toString()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Write this message to a {@link Swift_InputByteStream}.
     */
    public function toByteStream(Swift_InputByteStream $is)
    {
        if (count($children = $this->getChildren()) > 0 && '' != $this->getBody()) {
            $this->setChildren(array_merge([$this->becomeMimePart()], $children));
            parent::toByteStream($is);
            $this->setChildren($children);
        } else {
            parent::toByteStream($is);
        }
    }

    /** @see Swift_Mime_SimpleMimeEntity::getIdField() */
    protected function getIdField()
    {
        return 'Message-ID';
    }

    /** Turn the body of this message into a child of itself if needed */
    protected function becomeMimePart()
    {
        $part = new parent($this->getHeaders()->newInstance(), $this->getEncoder(),
            $this->getCache(), $this->getIdGenerator(), $this->userCharset
            );
        $part->setContentType($this->userContentType);
        $part->setBody($this->getBody());
        $part->setFormat($this->userFormat);
        $part->setDelSp($this->userDelSp);
        $part->setNestingLevel($this->getTopNestingLevel());

        return $part;
    }

    /** Get the highest nesting level nested inside this message */
    private function getTopNestingLevel()
    {
        $highestLevel = $this->getNestingLevel();
        foreach ($this->getChildren() as $child) {
            $childLevel = $child->getNestingLevel();
            if ($highestLevel < $childLevel) {
  