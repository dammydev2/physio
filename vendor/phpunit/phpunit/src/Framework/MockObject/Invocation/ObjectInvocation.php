ntime;
    }

    /**
     * @throws \PHPUnit\Runner\Exception
     * @throws Exception
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \ReflectionException
     */
    public function doRun(Test $suite, array $arguments = [], bool $exit = true): TestResult
    {
        if (isset($arguments['configuration'])) {
            $GLOBALS['__PHPUNIT_CONFIGURATION_FILE'] = $arguments['configuration'];
        }

        $this->handleConfiguration($arguments);

        if (\is_int($arguments['columns']) && $arguments['columns'] < 16) {
            $arguments['columns']   = 16;
            $tooFewColumnsRequested = true;
        }

        if (isset($arguments['bootstrap'])) {
            $GLOBALS['__PHPUNIT_BOOTSTRAP'] = $arguments['bootstrap'];
        }

        if ($suite instanceof TestCase || $suite instanceof TestSuite) {
            if ($arguments['backupGlobals'] === true) {
                $suite