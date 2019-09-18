12_0.php',
            'qux_2_0.php',
        ]), $finder->in(self::$tmpDir)->getIterator());
    }

    public function testSortByModifiedTime()
    {
        $finder = $this->buildFinder();
        $this->assertSame($finder, $finder->sortByModifiedTime());
        $this->assertIterator($this->toAbsolute([
            'foo/bar.tmp',
            'test.php',
            'toto',
            'test.py',
            'foo',
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

    public function testReverseSorting()
    {
        $finder = $this->buildFinder();
        $this->assertSame($finder, $finder->sortByName(