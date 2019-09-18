oader($this->arguments['loader']);
        }

        if (isset($this->arguments['configuration']) &&
            \is_dir($this->arguments['configuration'])) {
            $configurationFile = $this->arguments['configuration'] . '/phpunit.xml';

            if (\file_exists($configurationFile)) {
                $this->arguments['configuration'] = \realpath(
                    $configurationFile
                );
            } elseif (\file_exists($configurationFile . '.dist')) {
                $this->arguments['configuration'] = \realpath(
                    $configurationFile . '.dist'
                );
            }
        } elseif (!isset($this->arguments['configuration']) &&
            $this->arguments['useDefaultConfiguration']) {
            if (\