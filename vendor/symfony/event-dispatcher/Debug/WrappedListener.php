($this->toAbsolute([
            'foo',
            'foo/bar.tmp',
            'test.php',
            'test.py',
            'toto',
            'foo bar',
            'qux',
            'qux/baz_100_1.py',
            'qux/baz_1_2.py',
            'qux_0_1.php',
            'qux_1000_1.php',
            'qux_1002_0.php',
            'qux_10_2.php',
            'qux_12_0.php',
            'qux_2_0.php',
        ]), $finder->in(self::$tmpDir)->getIterator());
    }

    public function testIn()
    {
        $finder = $this->buildFinder();
        $iterator = $finder->files()->name('*.php')->depth('< 1')->in([self::$tmpDir, __DIR__])->getIterator();

        $expected = [
            self::$tmpDir.\DIRECTORY_SEPARATOR.'test.php',
            __DIR__.\DIRECTORY_SEPARATOR.'FinderTest.php',
            __DIR__.\DIRECTORY_SEPARATOR.'GlobTest.php',
            self::$tmpDir.\DIRECTORY_SEPARATOR.'qux_0_1.php',
            self::$tmpDir.\DIRECTORY_SEPARATOR.'qux_1000_1.php',
            self::$tmpDir.\DIRECTORY_SEPARATOR.'qux_1002_0.php',
            self::$tmpDir.\DIRECTORY_SEPARATOR.'qux_10_2.php',
            self::$tmpDir.\DIRECTORY_SEPARATOR.'qux_12_0.php',
            self::$tmpDir.\DIRECTORY_SEPARATOR.'qux_2_0.php',
        ];

        $this->assertIterator($expected, $iterator);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInWithNonExistentDirectory()
    {
        $finder = new Finder();
        $finder->in('foobar');
    }

    public function testInWithGlob()
    {
        $finder = $this->buildFinder();
        $finder->in([__DIR__.'/Fixtures/*/B/C/', __DIR__.'/Fixtures/*/*/B/C/'])->getIterator();

        $this->assertIterator($this->toAbsoluteFixtures(['A/B/C/abc.dat', 'copy/A/B/C/abc.dat.copy']), $finder);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInWithNonDirectoryGlob()
    {
        $finder = new Finder();
        $finder->in(__DIR__.'/Fixtures/A/a*');
    }

    public function testInWithGlobBrace()
    {
        if (!\defined('GLOB_BRACE')) {
            $this->markTestSkipped('Glob brace is not supported on this system.');
        }

        $finder = $this->buildFinder();
        $finder->in([__DIR__.'/Fixtures/{A,copy/A}/B/C'])->getIterator();

        $this->assertIterator($this->toAbsoluteFixtures(['A/B/C/abc.dat', 'copy/A/B/C/abc.dat.copy']), $finder);
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetIteratorWithoutIn()
    {
        $finder = Finder::create();
        $finder->getIterator();
    }

    public function testGetIterator()
    {
        $finder = $this->buildFinder();
        $dirs = [];
        foreach ($finder->directories()->in(self::$tmpDir) as $dir) {
            $dirs[] = (string) $dir;
        }

        $expected = $this->toAbsolute(['foo', 'qux', 'toto']);

        sort($dirs);
        sort($expected);

        $this->assertEquals($expected, $dirs, 'implements the \IteratorAggregate interface');

        $finder = $this->buildFinder();
        $this->assertEquals(3, iterator_count($finder->directories()->in(self::$tmpDir)), 'implements the \IteratorAggregate interface');

        $finder = $this->buildFinder();
        $a = iterator_to_array($finder->directories()->in(self::$tmpDir));
        $a = array_values(array_map('strval', $a));
        sort($a);
        $this->assertEquals($expected, $a, 'implements the \IteratorAggregate interface');
    }

    public function testRelativePath()
    {
        $finder = $this->buildFinder()->in(self::$tmpDir);

        $paths = [];

        foreach ($finder as $file) {
     