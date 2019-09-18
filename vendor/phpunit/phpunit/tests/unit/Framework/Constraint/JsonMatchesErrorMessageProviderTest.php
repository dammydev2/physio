   $this->assertEquals('bar', $_POST['foo']);
        $this->assertEquals('bar', $_GET['foo']);
        $this->assertEquals('bar', $_COOKIE['foo']);
        $this->assertEquals('bar', $_SERVER['foo']);
        $this->assertEquals('bar', $_FILES['foo']);
        $this->assertEquals('bar', $_REQUEST['foo']);

        \ini_set('highlight.keyword', $savedIniHighlightKeyword);
        \ini_set('highlight.string', $savedIniHighlightString);
    }

    /**
     * @backupGlobals enabled
     *
     * @see https://github.com/sebastianbergmann/phpunit/issues/1181
     */
    public function testHandlePHPConfigurationDoesNotOverwrittenExistingEnvArrayVariables(): void
    {
        $_ENV['foo'] = false;
        $this->configuration->handlePHPConfiguration();

        $this->assertFalse($_ENV['foo']);
        $this->assertEquals('forced', \getenv('foo_force'));
    }

    /**
     * @backupGlobals enabled
     *
     * @see https://github.com/sebastianbergmann/phpunit/issues/2353
     */
    public function testHandlePHPConfigurationDoesForceOverwrittenExistingEnvArrayVariables(): void
    {
        $_ENV['foo_force'] = false;
        $this->configuration->handlePHPConfiguration();

        $this->assertEquals('forced', $_ENV['foo_force']);
        $this->assertEquals('forced', \getenv('foo_force'));
    }

    /**
     * @backupGlobals enabled
     *
     * @see https://github.com/sebastianbergmann/phpunit/issues/1181
     */
    public function testHandlePHPConfigurationDoesNotOverriteVariablesFromPutEnv(): void
    {
        $backupFoo = \getenv('foo');

        \putenv('foo=putenv');
        $this->configuration->handlePHPConfiguration();

        $this->assertEquals('putenv', $_ENV['foo']);
        $this->assertEquals('putenv', \getenv('foo'));

        if ($backupFoo === false) {
            \putenv('foo');     // delete variable from environment
        } else {
            \putenv("foo=$backupFoo");
        }
    }

    /**
     * @backupGlobals enabled
     *
     * @see https://github.com/sebastianbergmann/phpunit/issues/1181
     */
    public function testHandlePHPConfigurationDoesOverwriteVariablesFromPutEnvWhenForced(): void
    {
        \putenv('foo_force=putenv');
        $this->configuration->handlePHPConfiguration();

        $this->assertEquals('forced', $_ENV['foo_force']);
        $this->assertEquals('forced', \getenv('foo_force'));
    }

    public function testPHPUnitConfigurationIsReadCorrectly(): void
    {
        $this->assertEquals(
            [
                'backupGlobals'                              => true,
                'backupStaticAttributes'                     => false,
                'beStrictAboutChangesToGlobalState'          => false,
                'boot