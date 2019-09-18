bose')) {
            $result['verbose'] = $this->getBoolean(
                (string) $root->getAttribute('verbose'),
                false
            );
        }

        if ($root->hasAttribute('registerMockObjectsFromTestArgumentsRecursively')) {
            $result['registerMockObjectsFromTestArgumentsRecursively'] = $this->getBoolean(
                (string) $root->getAttribute('registerMockObjectsFromTestArgumentsRecursively'),
                false
            );
        }

        if ($root->hasAttribute('extensionsDirectory')) {
            $result['extensionsDirectory'] = $this->toAbsolutePath(
                (string) $root->getAttribute(
                    'extensionsDirectory'
                )
            );
        }

        if ($root->hasAttribute('cacheResult')) {
            $result['cacheResult'] = $this->getBoolean(
                (string) $root->getAttribute('cacheResult'),
                false
            );
        }

        if ($root->hasAttribu