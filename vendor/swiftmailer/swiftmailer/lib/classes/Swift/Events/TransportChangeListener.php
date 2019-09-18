ns;
    }

    /**
     * Render a RFC 2047 compliant header parameter from the $name and $value.
     *
     * @param string $name
     * @param string $value
     *
     * @return string
     */
    private function createParameter($name, $value)
    {
        $origValue = $value;

        $encoded = false;
        // Allow room for parameter name, indices, "=" and DQUOTEs
        $maxValueLength = $this->getMaxLineLength() - strlen($name.'=*N"";') - 1;
        $firstLineOffset = 0;

        // If it's not already a valid parameter value...
        if (!preg_match('/^'.self::TOKEN_REGEX.'$/D', $value)) {
            // TODO: text, or something else??
            // ... and it's not ascii
            if (!preg_match('/^[\x00-\x08\x0B\x0C\x0E-\x7F]*$/D', $value)) {
                $encoded = true;
                // Allow space for the indices, charset and language
                $maxValueLength = $this->getMaxLineLength() - strlen($name.'*N*="";') - 1;
                $firstLineOffset = strlen(
                    $this->getCharset()."'".$this->getLanguage()."'"
                    );
            }
        }

        // Encode if we need to
        if ($encoded || strlen($value) > $maxValueLength) {
            if 