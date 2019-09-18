dition) {
            $this->setCachedValue(null);
        }
    }

    /**
     * Generate a list of all tokens in the final header.
     *
     * @param string $string The string to tokenize
     *
     * @return array An array of tokens as strings
     */
    protected function toTokens($string = null)
    {
        if (null === $string) {
            $string = $this->getFieldBody();
        }

        $tokens = [];

        // Generate atoms; split at all invisible boundaries followed by WSP
        foreach (preg_split('~(?=[ \t])~', $string) as $token) {
            $newTokens = $this->generateTokenLines($token);
            foreach ($newTokens as $newToken) {
                $tokens[] = $newToken;
            }
        }

        return $tokens;
    }

    /**
