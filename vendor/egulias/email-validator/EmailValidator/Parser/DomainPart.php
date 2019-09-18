   return [];
            }
        };
    }

    /**
     * Returns all the extra data tables registered with this handler.
     * Optionally accepts a 'label' parameter, to only return the data
     * table under that label.
     * @param  string|null      $label
     * @return array[]|callable
     */
    public function getDataTables($label = null)
    {
        if ($label !== null) {
            return isset($this->extraTables[$label]) ?
                   $this->extraTables[$label] : [];
        }

        return $this->extraTables;
    }

    /**
     * Allows to disable all attempts to dynamically decide whether to
     * handle or return prematurely.
     * Set this to ensure that the handler will perform no matter what.
     * @param  bool|null $value
     * @return bool|null
     */
    public function handleUnconditionally($value = null)
    {
        if (func_num_args() == 0) {
            return $this->handleUnconditionally;
        }

        $this->handleUnconditionally = (bool) $value;
    }

    /**
     * Adds an editor resolver, identified by a string
     * name, and that may be a string path, or a callable
     * resolver. If the callable returns a string, it will
     * be set as the file reference's href attribute.
     *
     * @example
     *  $run->addEditor('macvim', "mvim://open?url=file://%file&line=%line")
     * @example
     *   $run->addEditor('remove-it', function($file, $line) {
     *       unlink($file);
     *       return "http://stackoverflow.com";
     *   });
     * @param string $identifier
     * @param string $resolver
     */
    public function addEditor($identifier, $resolver)
    {
        $this->editors[$identifier] = $resolver;
    }

    /**
     * Set the editor to use to open referenced files, by a string
     * identifier, or a callable that will be executed for every
     * file reference, with a $file and $line argument, and should
     * return a string.
     *
     * @example
     *   $run->setEditor(function($file, $line) { return "file:///{$file}"; });
     * @example
     *   $run->setEditor('sublime');
     *
     * @throws InvalidArgumentException If invalid argument identifier provided
     * @param  string|callable          $editor
     */
    public function setEditor($editor)
    {
        if (!is_callable($editor) && !isset($this->editors[$editor])) {
            throw new InvalidArgumentException(
                "Unknown editor identifier: $editor. Known editors:" .
                implode(",", array_keys($this->editors))
            );
        }

        $this->editor = $editor;
    }

    /**
     * Given a string file path, and an integer file line,
     * executes the editor resolver and returns, if available,
     * a string that may be used as the href property for that
     * file reference.
     *
     * @throws InvalidArgumentException If editor resolver does not return a string
     * @param  string                   $filePath
     * @param  int                      $line
     * @return string|bool
     */
    public function getEditorHref($filePath, $line)
    {
        $editor = $this->getEditor($filePath, $line);

        if (empty($editor)) {
            return false;
        }

        // Check that the editor is a string, and replace the
        // %line and %file placeholders:
        if (!isset($editor['url']) || !is_string($editor['url'])) {
            throw new UnexpectedValueException(
                __METHOD__ . " should always resolve to a string or a valid editor array; got something else instead."
            );
        }

        $editor['url'] = str_replace("%line", rawurlencode($line), $editor['url']);
        $editor['url'] = str_replace("%file", rawurlencode($filePath), $editor['url']);

        return $editor['url'];
    }

    /**
     * Given a boolean if the editor link should
     * act as an Ajax request. The editor must be a
     * valid callable function/closure
     *
     * @throws UnexpectedValueException  If editor resolver does not return a boolean
     * @param  string                   $filePath
     * @param  int                      $line
     * @return bool
     */
    public function getEditorAjax($filePath, $line)
    {
        $editor = $this->getEditor($filePath, $line);

        // Check that the ajax is a bool
        if (!isset($editor['ajax']) || !is_bool($editor['ajax'])) {
            throw new UnexpectedValueException(
                __METHOD__ . " should always resolve to a bool; got something else instead."
            );
        }
        return $editor['ajax'];
    }

    /**
     * Given a boolean if the editor link should
     * act as an Ajax request. The editor must be a
     * valid callable function/closure
     *
     * @param  string $filePath
     * @param  int    $line
     * @return array
     */
    protected function getEditor($filePath, $line)
    {
        if (!$this->editor || (!is_string($this->editor) && !is_callable($this->editor))) {
            return [];
        }

        if (is_string($this->editor) && isset($this->editors[$this->editor]) && !is_callable($this->editors[$this->editor])) {
            return [
                'ajax' => false,
                'url' => $this->editors[$this->editor],
            ];
        }

        if (is_callable($this->editor) || (isset($this->editors[$this->editor]) && is_callable($this->editors[$this->editor]))) {
            if (is_callable($this->editor)) {
                $callback = call_user_func($this->editor, $filePath, $line);
            } else {
                $callback = call_user_func($this->editors[$this->editor], $filePath, $line);
            }

            if (empty($callback)) {
                return [];
            }

            if (is_string($callback)) {
                return [
                    'ajax' => false,
                    'url' => $callback,
                ];
            }

            return [
                'ajax' => isset($callback['ajax']) ? $callback['ajax'] : false,
                'url' => isset($callback['url']) ? $callback['url'] : $callback,
            ];
        }

        return [];
    }

    /**
     * @param  string $title
     * @return void
     */
    public function setPageTitle($title)
    {
        $this->pageTitle = (string) $title;
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Adds a path to the list of paths to be searched for
     * resources.
     *
     * @throws InvalidArgumentException If $path is not a valid directory
     *
     * @param  string $path
     * @return void
     */
    public function addResourcePath($path)
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException(
                "'$path' is not a valid directory"
            );
        }

        array_unshift($this->searchPaths, $path);
    }

    /**
     * Adds a custom css file to be loaded.
     *
     * @param  string $name
     * @return void
     */
    public function addCustomCss($name)
    {
        $this->customCss = $name;
    }

    /**
     * @return array
     */
    public function getResourcePaths()
    {
        return $this->searchPaths;
    }

    /**
     * Finds a resource, by its relative path, in all available search paths.
     * The search is performed starting at the last search path, and all the
     * way back to the first, enabling a cascading-type system of overrides
     * for all resources.
     *
     * @throws RuntimeException If resource cannot be found in any of the available paths
     *
     * @param  string $resource
     * @return string
     */
    protected function getResource($resource)
    {
        // If the resource was found before, we can speed things up
        // by caching its absolute, resolved path:
        if (isset($this->resourceCache[$resource])) {
            return $this->resourceCache[$resource];
        }

        // Search through available search paths, until we find the
        // resource we're after:
        foreach ($this->searchPaths as $path) {
            $fullPath = $path . "/$resource";

            if (is_file($fullPath)) {
                // Cache the result:
                $this->resourceCache[$resource] = $fullPath;
                return $fullPath;
            }
        }

        // If we got this far, nothing was found.
        throw new RuntimeException(
            "Could not find resource '$resource' in any resource paths."
            . "(searched: " . join(", ", $this->searchPaths). ")"
        );
    }

    /**
     * @deprecated
     *
     * @return string
     */
    public function getResourcesPath()
    {
        $allPaths = $this->getResourcePaths();

        // Compat: return only the first path added
        return end($allPaths) ?: null;
    }

    /**
     * @deprecated
     *
     * @param  string $resourcesPath
     * @return void
     */
    public function setResourcesPath($resourcesPath)
    {
        $this->addResourcePath($resourcesPath);
    }

    /**
     * Return the application paths.
     *
     * @return array
     */
    public function getApplicationPaths()
    {
        return $this->applicationPaths;
    }

    /**
     * Set the application paths.
     *
     * @param array $applicationPaths
     */
    public function setApplicationPaths($applicationPaths)
    {
        $this->applicationPaths = $applicationPaths;
    }

    /**
     * Set the application root path.
     *
     * @param string $applicationRootPath
     */
    public function setApplicationRootPath($applicationRootPath)
    {
        $this->templateHelper->setApplicationRootPath($applicationRootPath);
    }

    /**
     * blacklist a sensitive value within one of the superglobal arrays.
     *
     * @param $superGlobalName string the name of the superglobal array, e.g. '_GET'
     * @param $key string the key within the superglobal
     */
    public function blacklist($superGlobalName, $key)
    {
        $this->blacklist[$superGlobalName][] = $key;
    }

    /**
     * Checks all values within the given superGlobal array.
     * Blacklisted values will be replaced by a equal length string cointaining only '*' characters.
     *
     * We intentionally dont rely on $GLOBALS as it depends on 'auto_globals_jit' php.ini setting.
     *
     * @param $superGlobal array One of the superglobal arrays
     * @param $superGlobalName string the name of the superglobal array, e.g. '_GET'
     * @return array $values without sensitive data
     */
    private function masked(array $superGlobal, $superGlobalName)
    {
        $blacklisted = $this->blacklist[$superGlobalName];

        $values = $superGlobal;
        foreach ($blacklisted as $key) {
            if (isset($superGlobal[$key])) {
                $values[$key] = str_repeat('*', strlen($superGlobal[$key]));
            }
        }
        return $values;
    }
}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      