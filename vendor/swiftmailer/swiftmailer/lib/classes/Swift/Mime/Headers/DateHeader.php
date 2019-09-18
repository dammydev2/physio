e array_keys
            reset($sender); // Reset Pointer to first pos
            $path = key($sender); // Get key
        } elseif (!empty($from)) {
            reset($from); // Reset Pointer to first pos
            $path = key($from); // Get key
        }

        return $path;
    }

    /** Throw a TransportException, first sending it to any listeners */
    protected function throwException(Swift_TransportException $e)
    {
        if ($evt = $this->eventDispatcher->createTransportExceptionEvent($this, $e)) {
            $this->eventDispatcher->dispatchEvent($evt, 'exceptionThrown');
            if (!$evt->bubbleCancelled()) {
                throw $e;
            }
        } else {
            throw $e;
        }
    }

    /** Throws an Exception if a response code is incorrect */
    protected function assertResponseCode($response, $wanted)
    {
        if (!$response) {
            $this->throwException(new Swift_TransportException('Expected response code '.implode('/', $wanted).' but got an empty response'));
        }

        list($code) = sscanf($response, '%3d');
        $valid = (empty($wanted) || in_array($code, $wanted));

        if ($evt = $this->eventDispatcher->createResponseEvent($this, $response,
            $valid)) {
            $this->eventDispatcher->dispatchEvent($evt, 'responseReceived');
        }

        if (!$valid) {
            $this->throwException(new Swift_TransportException('Expected response code '.implode('/', $wanted).' but got code "'.$code.'", with message "'.$response.'"', $code));
        }
    }

    /** Get an entire multi-line response using its sequence number */
    protected function getFullResponse($seq)
    {
        $response = '';
        try {
            do {
                $line = $this->buffer->readLine($seq);
                $response .= $line;
            } while (null !== $line && false !== $line && ' ' != $line[3]);
        } catch (Swift_TransportException $e) {
            $this->throwException($e);
        } catch (Swift_IoException $e) {
            $this->throwException(new Swift_TransportException($e->getMessage(), 0, $e));
        }

        return $response;
    }

    /** Send an email to the given recipients from the given reverse path */
    private function doMailTransaction($message, $reversePath, array $recipients, array &$failedRecipients)
    {
        $sent = 0;
        $this->doMailFromCommand($reversePath);
        foreach ($recipients as $forwardPath) {
            try {
                $this->doRcptToCommand($forwardPath);
                ++$sent;
            } catch (Swift_TransportException $e) {
                $failedRecipients[] = $forwardPath;
    