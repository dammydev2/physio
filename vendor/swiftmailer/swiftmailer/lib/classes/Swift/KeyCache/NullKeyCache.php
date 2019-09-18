         }

            $children = (array) $message->getChildren();
            foreach ($children as $child) {
                list($type) = sscanf($child->getContentType(), '%[^/]/%s');
                if ('text' == $type) {
                    $body = $child->getBody();
                    $bodyReplaced = str_replace(
                        $search, $replace, $body
                        );
                    if ($body != $bodyReplaced) {
                        $child->setBody($bodyReplaced);
                        $this->originalChildBodies[$child->getId()] = $body;
                    }
                }
            }
            $this->lastMessage = $message;
        }
    }

    /**
     * Find a map of replacements for the address.
     *
     * If this plugin was provided with a delegate instance of
     * {@link Swift_Plugins_Decorator_Replacements} then the call will be
     * delegated to it.  Otherwise, it will attempt to find the replacements
     * from the array provided in the constructor.
     *
     * If no replacements can be found, an empty value (NULL) is returned.
     *
     * @param string $address
     *
     * @return array
     */
    public function getReplacementsFor($address)
    {
        if ($this->replacements instanceof Swift_Plugins_Decorator_Replacements) {
            return $this->replacements->getReplacementsFor($address);
        }

        return $this->replacements[$address] ?? null;
    }

    /**
     * Invoked immediately after the Message is sent.
     */
    public function sendPerformed(Swift_Events_SendEvent $evt)
    {
        $this->restoreMessage($evt->getMessage());
    }

    /** Restore a changed message back to its original state */
    private function restoreMessage(Swift_Mime_SimpleMessage $message)
    {
        if ($this->lastMessage === $message) {
            if (isset($this->originalBody)) {
                $message->setBody($this->originalBody);
                $this->originalBody = null;
            }
            if (!empty($this->originalHeaders)) {
                foreach ($message->getHeaders()->getAll() as $header) {
                    if (array_key_exists($header->getFieldName(), $this->originalHeaders)) {
                        $header->setFieldBodyModel($this->originalHeaders[$header->getFieldName()]);
                    }
                }
                $this->originalHeaders = [];
            }
            if (!empty($this->originalChildBodies)) {
                $children = (array) $message->getChildren();
                foreach ($children as $child) {
