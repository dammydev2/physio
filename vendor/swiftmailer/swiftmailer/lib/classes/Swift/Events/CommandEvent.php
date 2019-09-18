 ($this->tokenNeedsEncoding($token)) {
                // Don't encode starting WSP
                $firstChar = substr($token, 0, 1);
                switch ($firstChar) {
                    case ' ':
                    case "\t":
                        $value .= $firstChar;
                        $token = substr($token, 1);
                }

                if (-1 == $usedLength) {
                    $usedLength = strlen($header->getFieldName().': ') + strlen($value);
                }
                $value .= $this->getTokenAsEncodedWord($token, $usedLength);

                $header->setMaxLineLength(76); // Forcefully override
            } else {
                $value .= $token;
            }
        }

        return $value;
    }

    /**
     * Test if a token needs to be encoded or not.
     *
     * @param string $token
     *
     * @return bool
     */
    protected function tokenNeedsEncoding($token)
    {
        return preg_match('~[\x00-\x08\x10-\x19\x7F-\xFF\r\n]~', $token);
    }

    /**
     * Splits a string into tokens in blocks of words which can be encoded quickly.
     *
     * @param string $string
     *
     * @return string[]
     */
    protected function getEncodableWordTokens($string)
    {
        $tokens = [];

        $encodedToken = '';
        // Split at all whitespace boundaries
        foreach (preg_sp