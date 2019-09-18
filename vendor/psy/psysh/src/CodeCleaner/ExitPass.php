()'d code";
            }

            // Skip any lines that don't match our filter options
            if (!$this->filter->match(\sprintf('%s%s%s() at %s:%s', $class, $type, $function, $file, $line))) {
                continue;
            }

            $lines[] = \sprintf(
                ' <class>%s</class>%s%s() at <info>%s:%s</info>',
                OutputFormatter::escape($class),
                OutputFormatter::escape($type),
                OutputFormatter::escape($function),
                OutputFormatter::escape($file),
                OutputFormatter::escape($line)
            );
        }

        return $lines;
    }

    /**
     * Replace the given directory from the start of a filepath.
     *
     * @param string $c