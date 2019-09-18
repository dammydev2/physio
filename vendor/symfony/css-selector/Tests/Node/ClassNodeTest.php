is->isFinder ? $this->classLoader[0]->findFile($class) ?: null : null;
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $class The name of the class
     *
     * @throws \RuntimeException
     */
    public function loadClass($class)
    {
        $e = error_reporting(error_reporting() | E_PARSE | E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR);

        try {
            if ($this->isFinder && !isset($this->loaded[$class])) {
                $this->loaded[$class] = true;
                if (!$file = $this->classLoader[0]->findFile($class) ?: false) {
                    // no-op
                } elseif (\function_exists('opcache_is_script_cached') && @opcache_is_script_cached($file)) {
                    require $file;

                    return;
                } else {
                    require $