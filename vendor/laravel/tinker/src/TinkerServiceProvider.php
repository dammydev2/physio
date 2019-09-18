      return ['type' => 'dir', 'path' => $dirname];
    }

    /**
     * Create a directory.
     *
     * @param string   $directory
     * @param resource $connection
     *
     * @return bool
     */
    protected function createActualDirectory($directory, $connection)
    {
        // List the current directory
        $listing = ftp_nlist($connection, '.') ?: [];

        foreach ($listing as $key => $item) {
            if (preg_match('~^\./.*~', $item)) {
                $listing[$key] = substr($item, 2);
            }
        }

        if (in_array($directory, $listing, true)) {
            return true;
        }

        return (boolean) ftp_mkdir($connection, $directory);
    }

    /**
     * @inheritdoc
     */
    public function getMetadata($path)
    {
        if ($path === '') {
            return ['type' => 'dir', 'path' => ''];
        }

        if (@ftp_chdir($this->getConnection(), $path) === true) {
            $this->setConnectionRoot();

            return ['type' => 'dir', 'path' => $path];
        }

        $listing = $this->ftpRawlist('-A', str_replace('*', '\\*', $path));

        if (empty($listing) || in_array('total 0', $listing, true)) {
            return false;
        }

        if (preg_match('/.* not found/', $listing[0])) {
            return false;
        }

        if (preg_match('/^total [0-9]*$/', $listing[0])) {
        