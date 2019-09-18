 ($node->hasAttribute('phpVersion')) {
            $phpVersion = (string) $node->getAttribute('phpVersion');
        }

        if ($node->hasAttribute('phpVersionOperator')) {
            $phpVersionOperator = (string) $node->getAttribute('phpVersionOperator');
        }

        return \version_compare(\PHP_VERSION, $phpVersion, $phpVersionOperator);
    }

    /**
     * if $value is 'false' or 'true', this returns the value that $value represents.
     * Otherwise, returns $default, which may be a string in rare cases.
     * See PHPUnit\Util\ConfigurationTest::testPHPConfigurationIsReadCorrectly
     *
     * @param bool|string $default
     *
     * @return bool|string
     */
    private function getBoolean(string $value, $default)
    {
        if (\strtolower($value) === 'false') {
            return false;
        }

        if (\strtolower($value) === 'true') {
            return true;
        }

        return $defau