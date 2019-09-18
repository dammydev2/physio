lic function getLanguage()
    {
        return $this->lang;
    }

    /**
     * Set the encoder used for encoding the header.
     */
    public function setEncoder(Swift_Mime_HeaderEncoder $encoder)
    {
        $this->encoder = $encoder;
        $this->setCachedValue(null);
    }

    /**
     * Get the encoder used for encoding this Header.
     *
     * @return Swift_Mime_HeaderEncoder
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * Get the name of this header (e.g. charset).
     *
     * @return string
     */
    public function getFieldName()
    {
        return $this->name;
    }

    /**
     * Set the maximum length of lines in the header (excluding EOL).
     *
     * @param int $lineLength
     */
    public function setMaxLineLength($lineLength)
    {
        $this->clearCachedValueIf($this->lineLength != $lineLength);
        $this->lineLength = $lineLength;
    }

    /**
     * Get the maximum permitted length of lines in this Header.
     *
     * @return int
     */
    public function getMaxLineLength()
    {
        return $this->lineLength;
    }

    /**
     * Get this Header rendered as a RFC 2822 compliant string.
     *
     * @return string
     *
     * @throws Swift_RfcComplianceException
     */
    public function toString()
    {
        return $this->tokensToString($this->toTokens());
    }

    /**
     * Returns a string representation of this object.
     *
     * @return string
     *
     * @see toString()
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Set the name of this Header field.
     *
     * @param string $name
     */
    protected function setFieldName($name)
    {
        $this->name = $name;
    }

    /**
     * Produces a compliant, formatted RFC 2822 'phrase' based on the string given.
     *
     * @param string $string  as displayed
     * @param string $charset of the text
     * @param bool   $shorten the first line to make remove for header name
     *
     * @return string
     */
    protected function createPhrase(Swift_Mime_Header $header, $string, $charset, Swift_Mime_HeaderEncoder $encoder = null, $shorten = false)
    {
        // Treat token as exactly what was given
        $phraseStr = $string;
   