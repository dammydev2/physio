($path, $content)
    {
        $mimeType = MimeType::detectByContent($content);

        if ( ! (empty($mimeType) || in_array($mimeType, ['application/x-empty', 'text/plain', 'text/x-asm']))) {
            return $mimeType;
        }

        return MimeType::detectByFilename($path);
    }

    /**
     * Emulate directories.
     *
     * @param array $listing
     *
     * @return array listing with emulated directories
     */
    public static function emulateDirectories(array $listing)
    {
        $directories = [];
        $listedDirectories = [];

        foreach ($listing as $object) {
            list($directories, $listedDirectories) = static::emulateObjectDirectories($object, $directories, $listedDirectories);
        }

        $directories = array_diff(array_unique($directories), array_unique($listedDirectories));

        foreach ($directories as $directory) {
            $listing[] = static::pathinfo($directory) + ['type' => 'dir'];
        }

        return $listing;
    }

    /**
     * Ensure a Config instance.
     *
     * @param null|array|Config $config
     *
     * @return Config config instance
     *
     * @throw  LogicException
     */
    public static function ensureConfig($config)
    {
        if ($config === null) {
            re