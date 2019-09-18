
        global $a;
        global $i;

        $this->assertEquals('a', $a);
        $this->assertEquals('a', $GLOBALS['a']);
        $this->assertEquals('b', $_ENV['b']);
        $this->assertEquals('c', $_POST['c']);
        $this->assertEquals('d', $_GET['d']);
        $this->assertEquals('e', $_COOKIE['e']);
        $this->assertEquals('f', $_SERVER['f']);
        $this->assertEquals('g', $_FILES['g']);
        $this->assertEquals('h', $_REQUEST['h']);
        $this->assertEquals('ii', $i);
        $this->assertEquals('ii', $GLOBALS['i']);

        $this->assertArrayNotHasKey('foo', $GLOBALS);
    }

    /**
     * @backupGlobals enabled
     * @backupStaticAttributes enabled
     *
     * @doesNotPerformAssertions
     */
    public function testStaticAttributesBackupPre(): void
    {
        $GLOBALS['singleton'] = \Singleton::getInstance();
        $GLOBALS['i']         = 'not reset by backup';

        $GLOBALS['j']         = 'reset by backup';
        self::$testStatic     = 123;
    }

    /**
     * @depends testStaticAttributesBackupPre
     */
    public function testStaticAttributesBackupPost(): void
    {
        // Snapshots made by @backupGlobals
        $this->assertSame(\Singleton::getInstance(), $GLOBALS['singleton']);
        $this->assertSame('not reset by backup', $GLOBALS['i']);

        // Reset global
        $this->assertArrayN