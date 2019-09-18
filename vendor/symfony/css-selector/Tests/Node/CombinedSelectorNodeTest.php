erfaces[$parent] = $parent;

            if (!isset(self::$checkedClasses[$parent])) {
                $this->checkClass($parent);
            }

            if (isset(self::$final[$parent])) {
                $deprecations[] = sprintf('The "%s" class is considered final%s. It may change without further notice as of its next major version. You should not extend it from "%s".', $parent, self::$final[$parent], $class);
            }
        }

        // Detect if the parent is annotated
        foreach ($parentAndOwnInterfaces + \class_uses($class, false) as $use) {
            if (!isset(self::$checkedClasses[$use])) {
                $this->checkClass($use);
            }
            if (isset(self::$deprecated[$use]) && \strncmp($ns, \str_replace('_', '\\', $use), $len) && !isset(self::$deprecated[$class])) {
                $type = class_exists($class, false) ? 'class' : (interface_exists($class, false) ? 'interface' : 'trait');
                $verb = class_exists($use, false) || interface_exists($class, false) ? 'extends' : (interface_exists($use, false) ? 'implements' : 'uses');

                $deprecations[] = sprintf('The "%s" %s %s "%s" that is deprecated%s.', $class, $type, $verb, $use