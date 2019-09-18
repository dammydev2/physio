f (!\version_compare($phpunitVersion, $required['PHPUnit']['version'], $operator)) {
                $missing[] = \sprintf('PHPUnit %s %s is required.', $operator, $required['PHPUnit']['version']);
            }
        } elseif (!empty($required['PHPUnit_constraint'])) {
            $phpunitVersion = new \PharIo\Version\Version(self::sanitizeVersionNumber(Version::id()));

            if (!$required['PHPUnit_constraint']['constraint']->complies($phpunitVersion)) {
                $missing[] = \sprintf(
                    'PHPUnit version does not match the required constraint %s.',
                    $required['PHPUnit_constraint']['constraint']->asString()
                );
            }
        }

        if (!empty($required['OSFAMILY']) && $required['OSFAMILY'] !== (new OperatingSystem)->getFamily()) {
            $missing[] = \sprintf('Operating system %s is required.', $required['OSFAMILY']);
        }

        if (!empty($required['OS'])) {
            $requiredOsPattern = \sprintf('/%s/i', \addcslashes($required['OS'], '/'));

            if (!\preg_match($requiredOsPattern, \PHP_OS)) {
                $missing[] = \sprintf('Operating system matching %s is required.', $requiredOsPattern);
            }
        }

        if (!empty($required['functions'])) {
            foreach ($required['functions'] as $function) {
                $pieces = \explode('::', $function);

                if (\count($pieces) === 2 && \method_exists($pieces[0], $pieces[1])) {
                    continue;
                }

                if (\function_exists($function)) {
                    continue;
                }

                $missing[] = \sprintf('Function %s is required.', $function);
            }
        }

        if (!empty($required['setting'])) {
            foreach ($required['setting'] as $setting => $value) {
                if (\ini_get($setting) != $value) {
                    $missing[] = \sprintf('Setting "%s" must be "%s".', $setting, $value);
                }
            }
        }

        if (!empty($required['extensions'])) {
            foreach ($required['extensions'] as $extension) {
                if (isset($required['extension_versions'][$extension])) {
                    continue;
                }

                if (!\extension_loaded($extension)) {
                    $missing[] = \sprintf('Extension %s is required.', $extension);
                }
            }
        }

        if (!empty($required['extension_versions'])) {
            foreach ($required['extension_versions'] as $extension => $required) {
                $actualVersion = \phpversion($extension);

                $operator = empty($required['operator']) ? '>=' : $required['operator'];

                if ($actualVersion === false || !\version_compare($actualVersion, $required['version'], $operator)) {
                    $missing[] = \sprintf('Extension %s %s %s is required.', $extension, $operator, $required['version']);
                }
            }
        }

        return $missing;
    }

    /**
     * Returns the expected exception for a test.
     *
     * @return array|false
     */
    public static function getExpectedException(string $className, ?string $methodName)
    {
        $reflector  = new ReflectionMethod($className, $methodName);
        $docComment = $reflector->getDocComment();
        $docComment = \substr($docComment, 3, -2);

        if (\preg_match(self::REGEX_EXPECTED_EXCEPTION, $docComment, $matches)) {
            $annotations = self::parseTestMethodAnnotations(
                $className,
                $methodName
            );

            $class         = $matches[1];
            $code          = null;
            $message       = '';
            $messageRegExp = '';

            if (isset($matches[2])) {
                $message = \trim($matches[2]);
            } elseif (isset($annotations['method']['expectedExceptionMessage'])) {
                $message = self::parseAnnotationContent(
                    $annotati