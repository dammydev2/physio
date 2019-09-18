 $this->setVisibility($path, $visibility);
            $result['visibility'] = $visibility;
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function readStream($path)
    {
        $location = $this->applyPathPrefix($path);
        $stream = fopen($location, 'rb');

        return ['type' => 'file', 'path' => $path, 'stream' => $stream];
    }

    /**
     * @inheritdoc
     */
    public function updateStream($path, $resource, Config $config)
    {
        return $this->writeStream($path, $resource, $config);
    }

    /**
     * @inheritdoc
     */
    public function update($path, $contents, Config $config)
    {
        $location = $this->applyPathPrefix($path);
        $size = file_put_contents($location, $contents, $this->writeFlags);

        if ($size === false) {