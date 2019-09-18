ot find driver') {
                        throw new RuntimeException('SQLite PDO driver not found', 0, $e);
                    } else {
                        throw $e;
                    }
                }
            }
        }

        return $this->manualDb;
    }

    /**
     * Add an array of casters definitions.
     *
     * @param array $casters
     */
    public function addCasters(array $casters)
    {
        $this->getPresenter()->addCasters($casters);
    }

    /**
     * Get the Presenter service.
     *
     * @return Presenter
     */
    public function getPresenter()
    {
        if (!isset($this->presenter)) {
            $this->presenter = new Presenter($this->getOutput()->getFormatter(), $this->forceArrayIndexes());
        }

        return $this->presenter;
    }

    /**
     * Enable or disable warnings on multiple configuration or data files.
     *
     * @see self::warnOnMultipleConfigs()
     *
     * @param bool $warnOnMultipleConfigs
     */
    public function setWarnOnMultipleConfigs($warnOnMultipleConfigs)
    {
        $this->warnOnMultipleConfigs = (bool) $warnOnMultipleConfigs;
    }

    /**
     * Check whether to warn on multiple configuration or data files.
     *
     * By default, PsySH will use the file with highest precedence, and will
     * silently ignore all others. With this enabled, a warning will be emitted
     * (but not an exception thrown) if multiple configuration or data files
     * are found.
     *
     * This will default to true in a future release, but is false for now.
     *
     * @return bool
     */
    public function warnOnMultipleConfigs()
    {
        return $this->warnOnMultipleConfigs;
    }

    /**
     * Set the current color mode.
     *
     * @param string $colorMode
     */
    public function setColorMode($colorMode)
    {
        $validColorModes = [
            self::COLOR_MODE_AUTO,
            self::COLOR_MODE_FORCED,
            self::COLOR_MODE_DISABLED,
        ];

        if (\in_array($colorMode, $validColorModes)) {
            $this->colorMode = $colorMode;
        } else {
            throw new \InvalidArgumentException('invalid color mode: ' . $colorMode);
        }
    }

    /**
     * Get the current color mode.
     *
     * @return string
     */
    public function colorMode()
    {
        return $this->colorMode;
    }

    /**
     * Set an update checker service instance.
     *
  