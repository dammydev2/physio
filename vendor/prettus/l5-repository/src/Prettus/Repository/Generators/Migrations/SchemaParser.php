posix_getpid');

        if ($configFile = $this->getConfigFile()) {
            $this->loadConfigFile($configFile);
        }

        if (!$this->configFile && $localConfig = $this->getLocalConfigFile()) {
            $this->loadConfigFile($localConfig);
        }
    }

    /**
     * Get the current PsySH config file.
     *
     * If a `configFile` option was passed to the Configuration constructor,
     * this file will be returned. If not, all possible config directories will
     * be searched, and the first `config.php` or `rc.php` file which exists
     * will be returned.
     *
     * If you're trying to decide where to put your config file, pick
     *
     *     ~/.config/psysh/config.php
     *
     * @return string
     */
    public function getConfigFile()
    {
        if (isset($this->configFile)) {
            return $this->configFile;
        }

        $files = ConfigPaths::getConfigFiles(['config.php', 'rc.php'], $this->configDir);

        if (!empty($files)) {
            if ($this->warnOnMultipleConfigs && \count($files) > 1) {
                $msg = \sprintf('Multiple configuration files found: %s. Using %s', \implode($files, ', '), $files[0]);
                \trigger_error($msg, E_USER_NOTICE);
            }

            return $files[0];
        }
    }

    /**
     * Get the local PsySH config file.
     *
     * Searches for a project specific config file `.psysh.php` in the current
     * working directory.
     *
     * @return string
     */
    public function getLocalConfigFile()
    {
        $localConfig = \getcwd() . '/.psysh.php';

        if (@\is_file($localConfig)) {
            return $localConfig;
        }
    }

    /**
     * Load configuration values from an array of options.
     *
     * @param array $options
     */
    public function loadConfig(array $options)
    {
        foreach (self::$AVAILABLE_OPTIONS as $option) {
            if (isset($options[$option])) {
                $method = 'set' . \ucfirst($option);
                $this->$method($options[$option]);
            }
        }

        // legacy `tabCompletion` option
        if (isset($options['tabCompletion'])) {
            $msg = '`tabCompletion` is deprecated; use `useTabCompletion` instead.';
            @\trigger_error($msg, E_USER_DEPRECATED);

            $this->setUseTabCompletion($options['tabCompletion']);
        }

        foreach (['commands', 'matchers', 'casters'] as $option) {
            if (isset($options[$option])) {
                $method = 'add' . \ucfirst($option);
                $this->$method($options[$option]);
            }
        }

        // legacy `tabCompletionMatchers` option
        if (isset($options['tabCompletionMatchers'])) {
            $msg = '`tabCompletionMatchers` is deprecated; use `matchers` instead.';
            @\trigger_error($msg, E_USER_DEPRECATED);

            $this->addMatchers($options['tabCompletionMatchers']);
        }
    }

    /**
     * Load a configuration file (default: `$HOME/.config/psysh/config.php`).
     *
     * This configuration instance will be available to the config file as $config.
     * The config file may directly manipulate the configuration, or may return
     * an array of options which will be merged with the current configuration.
     *
     * @throws \InvalidArgumentException if the config file returns a non-array result
     *
     * @param string $file
     */
    public function loadConfigFile($file)
    {
        $__psysh_config_file__ = $file;
        $load = function ($config) use ($__psysh_config_file__) {
            $result = require $__psysh_config_file__;
            if ($result !== 1) {
                return $result;
            }
        };
        $result = $load($this);

        if (!empty($result)) {
            if (\is_array($result)) {
                $this->loadConfig($result);
            } else {
                throw new \InvalidArgumentException('Psy Shell configuration must return an array of options');
            }
        }
    }

    /**
     * Set files to be included by default at the start of each shell session.
     *
     * @param array $includes
     */
    public function setDefaultIncludes(array $includes = [])
    {
        $this->defaultIncludes = $includes;
    }

    /**
     * Get files to be included by default at the start of each shell session.
     *
     * @return array
     */
    public function getDefaultIncludes()
    {
        return $this->defaultIncludes ?: [];
    }

    /**
     * Set the shell's config directory location.
     *
     * @param string $dir
     */
    public function setConfigDir($dir)
    {
        $this->configDir = (string) $dir;
    }

    /**
     * Get the current configuration directory, if any is explicitly set.
     *
     * @return string
     */
    public function getConfigDir()
    {
        return $this->configDir;
    }

    /**
     * Set the shell's data directory location.
     *
     * @param string $dir
     