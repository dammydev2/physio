n" construct (as
     * defined in the HTTP specification), it is passed through verbatimly.
     */
    public static function unquote(string $s): string
    {
        return preg_replace('/\\\\(.)|"/', '$1', $s);
    }

    /**
     * Generates a HTTP Content-Disposition field-value.
     *
     * @param string $disposition      One of "inline" or "attachment"
     * @param string $filename         A unicode string
     * @param string $filenameFallback A string containing only ASCII characters that
     *                                 is semantically equivalent to $filename. If the filename is already ASCII,
     *                                 it can be omitted, or just copied from $filename
     *
     * @return string A string suitable for use as a Content-Disposition field-value
     *
     * @throws \InvalidArgumentException
     *
     * @see RFC 6266
     */
    public static function makeDisposition(string $disposition, string $filename, string $filenameFallback = ''): string
    {
        if (!\in_array($disposition, [self::DISPOSITION_ATTACHMENT, self::DISPOSITION_INLINE])) {
            throw new \InvalidArgumentException(sprintf('The disposition must be either "%s" or "%s".', self::DISPOSITION_ATTACHMENT, self::DISPOSITION_INLINE));
        }

        if ('' === $filenameFallback) {
            $filenameFallback = $filename;
        }

        // filenameFallback is not ASCII.
        if (!preg_match('/^[\x20