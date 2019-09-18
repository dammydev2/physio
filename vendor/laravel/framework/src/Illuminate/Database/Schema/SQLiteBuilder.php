s->createFlysystem(new LocalAdapter(
            $config['root'], $config['lock'] ?? LOCK_EX, $links, $permissions
        ), $config));
    }

    /**
     * Create an instance of the ftp driver.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function createFtpDriver(array $config)
    {
        return $this->adapt($this->createFlysystem(
            new FtpAdapter($config), $config
        ));
    }

    /**
     * Create an instance of the sftp driver.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function createSftpDriver(array $config)
    {
        return $this->adapt($this->createFlysystem(
            new SftpAdapter($config), $config
        ));
    }

    /**
     * Create an instance of the Amazon S3 driver.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Filesystem\Cloud
     */
    public function createS3Driver(array $config)
    {
        $s3Config = $this->formatS3Config($config);

        $root = $s3Config['root'] ?? null;

        $options = $config['options'] ?? [];

        return $this->adapt($this->createFlysystem(
            new S3Adapter(new S3Client($s3Config), $s3Config['bucket'], $root, $options)