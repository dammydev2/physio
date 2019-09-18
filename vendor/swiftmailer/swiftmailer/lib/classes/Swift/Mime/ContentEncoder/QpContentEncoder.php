ageStream->getPath(),
                $this->signCertificate,
                $this->signPrivateKey,
                [],
                $this->signOptions,
                $this->extraCerts
            )
        ) {
            throw new Swift_IoException(sprintf('Failed to sign S/Mime message. Error: "%s".', openssl_error_string()));
        }

        // Parse the resulting signed message content back into the Swift message
        // preserving the original headers
        $this->parseSSLOutput($signedMessageStream, $message);
    }

    /**
     * Encrypt a Swift message.
     */
    protected function smimeEncryptMessage(Swift_Message $message)
    {
        // If we don't have a certificate we can't encrypt the message
        if (null === $this->encryptCert) {
            return;
        }

        // Work on a clone of the original message
        $encryptMessage = clone $message;
        $encryptMessage->clearSigners();

        if ($this->wrapFullMessage) {
            // The original message essentially becomes the body of the new
            // wrapped message
            $encryptMessage = $this->wrapMimeMessage($encryptMessage);
        } else {
            // Only keep header needed to parse the body correctly
            $this->clearAllHeaders($encryptMessage);
            $this->copyHeaders(
                $message,
                $encryptMessage,
                [
                    'Content-Type',
                    'Content-Transfer-Encoding',
                    'Content-Disposition',
                ]
            );
        }

        // Convert the message content (including headers) to a string
        // and place it in a temporary file
        $messageStream = new Swift_ByteStream_TemporaryFileByteStream();
        $encryptMessage->toByteStream($messageStream);
        $messageStream->commit();
        $encryptedMessageStream = new Swift_ByteStream_TemporaryFileByteStream();

        // Encrypt the message
        if (!openssl_pkcs7_encrypt(
                $messageStream->getPath(),
                $encryptedMessageStream->getPath(),
                $this->encryptCert,
                [],
                0,
                $this->encryptCipher
            )
        ) {
            throw new Swift_IoException(sprintf('Failed to encrypt S/Mime message. Error: "%s".', openssl_error_string()));
        }

        // Parse the resulting signed message content back into the Swift message
        // preserving the original headers
        $this->parseSSLOutput($encryptedMessageStream, $message);
    }

    /**
     * Copy named headers from one Swift message to another.
     */
    protected function copyHeaders(
        Swift_Message $fromMessage,
        Swift_Message $toMessage,
        array $headers = []
    ) {
        foreach ($headers as $header) {
            $this->copyHeader($fromMessage, $toMessage, $header);
        }
    }

    /**
     * Copy a single header from one Swift message to another.
     *
     * @param string $headerName
     */
    protected function copyHeader(Swift_Message $fromMessage, Swift_Message $toMessage, $headerName)
    {
        $header = $fromMessage->getHeaders()->get($headerName);
        if (!$header) {
            return;
        }
        $headers = $toMessage->getHeaders();
        switch ($header->getFieldType()) {
            case Swift_Mime_Header::TYPE_TEXT:
                $headers->addTextHeader($header->getFieldName(), $header->getValue());
                break;
            case Swift_Mime_Header::TYPE_PARAMETERIZED:
                $headers->addParameterizedHeader(
                    $header->getFieldName(),
                    $header->getValue(),
                    $header->getParameters()
                );
                break;
        }
    }

    /**
     * Remove all headers from a Swift message.
     */
    protected function clearAllHeaders(Swift_Message $message)
    {
        $headers = $message->getHeaders();
        foreach ($headers->listAll() as $header) {
            $headers->removeAll($header);
        }
    }

    /**
     * Wraps a Swift_Message in a message/rfc822 MIME part.
     *
     * @return Swift_MimePart
     */
    prot