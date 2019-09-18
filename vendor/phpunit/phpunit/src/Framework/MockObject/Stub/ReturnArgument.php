   */
    public function handlePHPConfiguration(): void
    {
        $configuration = $this->getPHPConfiguration();

        if (!empty($configuration['include_path'])) {
            \ini_set(
                'include_path',
                \implode(\PATH_SEPARATOR, $configuration['include_path']) .
                \PATH_SEPARATOR .
                \ini_get('include_path')
            );
        }

        foreach ($configuration['ini'] as $name => $data) {
            $value = $data['value'];

            if (\defined($value)) {
                $value = (string) \constant($value);
            }

            \ini_set($name, $value);
        }

        foreach ($configuration['const'] as $name => $data) {
            $value = $data['value'];

            if (!\defined($name)) {
                \define($name, $value);
            }
        }

        foreach (['var', 'post', 'get', 'cookie', 'server', 'files', 'request'] as $array) {
            /*
             * @see https://github