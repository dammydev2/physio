& '' === $node->getAttribute('path')) {
            throw new \InvalidArgumentException(sprintf('The <route> element in file "%s" must have a "path" attribute or <path> child nodes.', $path));
        }

        if ($paths && '' !== $node->getAttribute('path')) {
            throw new \InvalidArgumentException(sprintf('The <route> element in file "%s" must not have both a "path" attribute and <path> child nodes.', $path));
        }

        if (!$paths) {
            $route = new Route($node->getAttribute('path'), $defaults, $requirements, $options, $node->getAttribute('host'), $schemes, $methods, $condition);
            $collection->add($id, $route);
        } else {
            foreach ($paths as $locale => $p) {
                $defaults['_locale'] = $locale;
                $defaults['_canonical_route'] = $id;
                $route = new Route($p, $defaults, $requirements, $options, $node->getAttribute('host'), $schemes, $methods, $condition);
                $collection->add($id.'.'.$locale, $route);
            }
        }
    }

    /**
     * Parses an import and adds the routes in the resource to the RouteCollection.
     *
     * @param RouteCollection $collection RouteCollection instance
     * @param \DOMElement     $node       Element to parse that represents a Route
     * @param string          $path       Full path of the XML file being processed
     * @param string          $file       Loaded file name
     *
     * @throws \InvalidArgumentException When the XML is invalid
     */
    protected function parseImport(RouteCollection $collection, \DOMElement $node, $path, $file)
    {
        if ('' === $resource = $node->getAttribute('resource')) {
            throw new \InvalidArgumentException(sprintf('The <import> element in file "%s" must have a "resource" attribute.', $path));
        }

        $type = $node->getAttribute('type');
        $prefix = $node->getAttribute('prefix');
        $host = $node->hasAttribute('host') ? $node->getAttribute('host') : null;
        $schemes = $node->hasAttribute('schemes') ? preg_split('/[\s,\|]++/', $node->getAttribute('schemes'), -1, PREG_SPLIT_NO_EMPTY) : null;
        $methods = $node->hasAttribute('methods') ? preg_split('/[\s,\|]++/', $node->getAttribute('methods'), -1, PREG_SPLIT_NO_EMPTY) : null;
        $trailingSlashOnRoot = $node->hasAttribute('trailing-slash-on-root') ? XmlUtils::phpize($node->getAttribute('trailing-slash-on-root')) : true;

        list($defaults, $requirements, $options, $condition, /* $paths */, $prefixes) = $this->parseConfigs($node, $path);

        if ('' !== $prefix && $prefixes) {
            throw new \InvalidArgumentException(sprintf('The <route> element in file "%s" must not have both a "prefix" attribute and <prefix> child nodes.', $path));
        }

        $this->setCurrentDir(\dirname($path));

        $imported = $this->import($resource, ('' !== $type ? $type : null), false, $file);

        if (!\is_array($imported)) {
            $imported = [$imported];
        }

        foreach ($imported as $subCollection) {
            /* @var $subCollection RouteCollection */
            if ('' !== $prefix || !$prefixes) {
                $subCollection->addPrefix($prefix);
                if (!$trailingSlashOnRoot) {
                    $rootPath = (new Route(trim(trim($prefix), '/').'/'))->getPath();
                    foreach ($subCollection->all() as $route) {
                        if ($route->getPath() === $rootPath) {
                            $route->setPath(rtrim($rootPath, '/'));
                        }
                    }
                }
            } else {
                foreach ($prefixes as $locale => $localePrefix) {
                    $prefixes[$locale] = trim(trim($localePrefix), '/');
                }
                foreach ($subCollection->all() as $name => $route) {
                    if (null === $locale = $route->getDefault('_locale')) {
                        $subCollection->remove($name);
                        foreach ($prefixes as $locale => $localePrefix) {
                            $localizedRoute = clone $route;
                            $localizedRoute->setPath($localePrefix.(!$trailingSlashOnRoot && '/' === $route->getPath() ? '' : $route->getPath()));
                            $localizedRoute->setDefault('_locale', $locale);
                            $localizedRoute->setDefault('_canonical_route', $name);
                            $subCollection->add($name.'.'.$locale, $localizedRoute);
                        }
                    } elseif (!isset($prefixes[$locale])) {
                        throw new \InvalidArgumentException(sprintf('Route "%s" with locale "%s" is missing a corresponding prefix when imported in "%s".', $name, $locale, $path));
                    } else {
                        $route->setPath($prefixes[$locale].(!$trailingSlashOnRoot && '/' === $route->getPath() ? '' : $route->getPath()));
                        $subCollection->add($name, $route);
                    }
                }
            }

            if (null !== $host) {
                $subCollection->setHost($host);
            }
            if (null !== $condition) {
                $subCollection->setCondition($condition);
            }
            if (null !== $schemes) {
                $subCollection->setSchemes($schemes);
            }
            if (null !== $methods) {
                $subCollection->setMethods($methods);
            }
            $subCollection->addDefaults($defaults);
            $subCollection->addRequirements($requirements);
            $subCollection->addOptions($options);

            if ($namePrefix = $node->getAttribute('name-prefix')) {
                $subCollection->addNamePrefix($namePrefix);
            }

            $collection->addCollection($subCollection);
        }
    }

    /**
     * Loads an XML file.
     *
     * @param string $file An XML file path
     *
     * @return \DOMDocument
     *
     * @throws \InvalidArgumentException When loading of XML file fails because of syntax errors
     *                                   or when the XML structure is not as expected by the scheme -
     *                                   see validate()
     */
    protected function loadFile($file)
    {
        return XmlUtils::loadFile($file, __DIR__.static::SCHEME_PATH);
    }

    /**
     * Parses the config elements (default, requirement, option).
     *
     * @param \DOMElement $node Element to parse that contains the configs
     * @param string      $path Full path of the XML file being processed
     *
     * @return array An array with the defaults as first item, requirements as second and options as third
     *
     * @throws \InvalidArgumentException When the XML is invalid
     */
    private function parseConfigs(\DOMElement $node, $path)
    {
        $defaults = [];
        $requirements = [];
        $options = [];
        $condition = null;
        $prefixes = [];
        $paths = [];

        /** @var \DOMElement $n */
        foreach ($node->getElementsByTagNameNS(self::NAMESPACE_URI, '*') as $n) {
            if ($node !== $n->parentNode) {
                continue;
            }

            switch ($n->localName) {
                case 'path':
                    $paths[$n->getAttribute('locale')] = trim($n->textContent);
                    break;
                case 'prefix':
                    $prefixes[$n->getAttribute('locale')] = trim($n->textContent);
                    break;
                case 'default':
                    if ($this->isElementValueNull($n)) {
                        $defaults[$n->getAttribute('key')] = null;
                    } else {
                        $defaults[$n->getAttribute('key')] = $this->parseDefaultsConfig($n, $path);
                    }

                    break;
                case 'requirement':
                    $requirements[$n->getAttribute('key')] = trim($n->textContent);
                    break;
                case 'option':
                    $options[$n->getAttribute('key')] = XmlUtils::phpize(trim($n->textContent));
                    break;
                case 'condition':
                    $condition = trim($n->textContent);
                    break;
                default:
                    throw new \InvalidArgumentException(sprintf('Unknown tag "%s" used in file "%s". Expected "default", "requirement", "option" or "condition".', $n->localName, $path));
            }
        }

        if ($controller = $node->getAttribute('controller')) {
            if (isset($defaults['_controller'])) {
                $name = $node->hasAttribute('id') ? sprintf('"%s"', $node->getAttribute('id')) : sprintf('the "%s" tag', $node->tagName);

                throw new \InvalidArgumentException(sprintf('The routing file "%s" must not specify both the "controller" attribute and the defaults key "_controller" for %s.', $path, $name));
            }

            $defaults['_controller'] = $controller;
        }

        return [$defaults, $requirements, $options, $condition, $paths, $prefixes];
    }

    /**
     * Parses the "default" elements.
     *
     * @param \DOMElement $element The "default" element to parse
     * @param string      $path    Full path of the XML file being processed
     *
     * @return array|bool|float|int|string|null The parsed value of the "default" element
     */
    private function parseDefaultsConfig(\DOMElement $element, $path)
    {
        if ($this->isElementValueNull($element)) {
            return;
        }

        // Check for existi