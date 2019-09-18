$root = $this->getRoot();
        $connection = $this->connection;

        if ($root && ! ftp_chdir($connection, $root)) {
            throw new RuntimeException('Root is invalid or does not exist: ' . $this->getRoot());
        }

        // Store absolute path for further reference.
        // This is needed when creating directories and
        // initial root was a relative path, else the root
        // would be relative to the chdir'd path.
        $this->root = ftp_pwd($connection);
    }

    /**
     * Login.
     *
     * @throws RuntimeException
     */
    protected function login()
    {
        set_error_handler(function () {});
        $isLoggedIn = ftp_login(
            $this->connection,
            $this->getUsername(),
            $this->getPassword()
        );
        restore_error_handler();

        if ( ! $isLoggedIn) {
            $this->disconnect();
            throw new RuntimeException(
                'Could not login with connection: ' . $this->getHost() . '::' . $this->getPort(
                ) . ', username: ' . $this->getUsername()
            );
        }
    }

    /**
     * Disconnect from the FTP server.
     */
    public function disconnect()
    {
        if (is_resource($this->connection)) {
            ftp_close($this->connection);
        }

        $this->connection = null;
    }

    /**
     * @inheritdoc
     */
    public function write($path, $contents, Config $config)
    {
        $stream = fopen('php://temp', 'w+b');
        fwrite($stream, $contents);
        rewind($stream);
        $result = $this->writeStream($path, $stream, $config);
        fclose($stream);

        if ($result === false) {
            return false;
        }

        $result['contents'] = $contents;
        $result['mimetype'] = Util::guessMimeType($path, $contents);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function writeStream($path, $resource, Config $config)
    {
        $this->ensureDirectory(Util::dirname($path));

        if ( ! ftp_fput($this->getConnection(), $path, $resource, $this->transferMode)) {
            return false;
        }

        if ($visibility = $config->get('visibility')) {
            $this->setVisibility($path, $visibility);