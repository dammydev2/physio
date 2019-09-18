der->in(self::$tmpDir)->getIterator());

        $finder = $this->buildFinder();
        $finder->name('test.ph*');
        $finder->name('test.py');
        $finder->notName('*.p{hp,y}');
        $this->assertIterator([], $finder->in(self::$tmpDir)->getIterator());
    }

    public function testNotNameWithArrayParam()
    {
        $finder = $this->buildFinder();
        $finder->notName(['*.php', '*.py']);
        $this->assertIterator($this->toAbsolute([
            'foo',
            'foo/bar.tmp',
            'toto',
            'foo bar',
            'qux',
        ]), $finder->in(self::$tmpDir)->getIterator());
    }

    /**
     * @dataProvider getRegexNameTestData
     */
    public function testRegexName($regex)
    {
        $finder = $this->buildFinder();
        $finder->name($regex);
        $this->assertIterator($this->toAbsolute([
            'test.py',
            'test.php',
        ]), $finder->in(self::$tmpDir)->getIterator());
    }

    public function testSize()
    {
        $finder = $this->buildFinder();
        $this->assertSame($finder, $finder->files()->size('< 1K')->size('> 500'));
        $this->assertIterator($this->toAbsolute(['test.php']), $finder->in(self::$tmpDir)->getIterator());
    }

    public function testSizeWithArrayParam()
    {
        $finder = $this->buildFinder();
        $this->assertSame($finder, $finder->files()->size(['< 1K', '> 500']));
        $this->assertIterator($this->toAbsolute(['test.php']), $finder->in(self::$tmpDir)->getIterator());
    }

    public function testDate()
    {
        $finder = $this->buildFinder();
        $this->assertSame($finder, $finder->files()->date('until last month'));
        $this->assertIterator($this->toAbsolute(['foo/bar.tmp', 'test.php']), $finder->in(self::$tmpDir)->getIterator());
    }

    public function testDateWithArrayParam()
    {
        $finder = $this->buildFinder();
        $this->assertSame($finder, $finder->files()->date(['>= 2005-10-15', 'until last month']));
        $this->assertIterator($this->toAbsolute(['foo/bar.tmp', 'test.php']), $finder->in(self::$tmpDir)->getIterator());
    }

    public function testExclude()
    {
        $finder = $this->buildFinder();
        $this->assertSame($finder, $finder->exclude('foo'));
        $this->assertIterator($this->toAbsolute([
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

    public function testIgnoreVCS()
    {
        $finder = $this->buildFinder();
        $this->assertSame($finder, $finder->ignoreVCS(false)->ignoreDotFiles(false));
        $this->assertIterator($this->toAbsolute([
            '.git',
            'foo',
            'foo/bar.tmp',
            'test.php',
            'test.py',
            'toto',
            'toto/.git',
            '.bar',
            '.foo',
            '.foo/.bar',
            '.foo/bar',
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

        $finder = $this->buildFinder();
        $finder->ignoreVCS(false)->ignoreVCS(false)->ignoreDotFiles(false);
        $this->assertIterator($this->toAbsolute([
            '.git',
            'foo',
            'foo/bar.tmp',
            'test.php',
            'test.py',
            'toto',
            'toto/.git',
            '.bar',
            '.foo',
            '.foo/.bar',
            '.foo/bar',
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

        $finder = $this->buildFinder();
        $this->assertSame($finder, $finder->ignoreVCS(true)->ignoreDotFiles(false));
        $this->assertIterator($this->toAbsolute([
            'foo',
            'foo/bar.tmp',
            'test.php',
            'test.py',
            'toto',
            '.bar',
            '.foo',
            '.foo/.bar',
            '.foo/bar',
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

    public function testIgnoreVCSCanBeDisabledAfterFirstIteration()
    {
        $finder = $this->buildFinder();
        $finder->in(self::$tmpDir);
        $finder->ignoreDotFiles(false);

        $this->assertIterator($this->toAbsolute([
            'foo',
            'foo/bar.tmp',
            'qux',
            'qux/baz_100_1.py',
            'qux/baz_1_2.py',
            'qux_0_1.php',
            'qux_1000_1.php',
            'qux_1002_0.php',
            'qux_10_2.php',
            'qux_12_0.php',
            'qux_2_0.php',
            'test.php',
            'test.py',
            'toto',
            '.bar',
            '.foo',
            '.foo/.bar',
            '.foo/bar',
            'foo bar',
        ]), $finder->getIterator());

        $finder->ignoreVCS(false);
        $this->assertIterator($this->toAbsolute(['.git',
            'foo',
            'foo/bar.tmp',
            'qux',
            'qux/baz_100_1.py',
            'qux/baz_1_2.py',
            'qux_0_1.php',
            'qux_1000_1.php',
            'qux_1002_0.php',
            'qux_10_2.php',
            'qux_12_0.php',
            'qux_2_0.php',
            'test.php',
            'test.py',
            'toto',
            'toto/.git',
            '.bar',
            '.foo',
            '.foo/.bar',
            '.foo/bar',
            'foo bar',
        ]), $finder->getIterator());
    }

    public function testIgnoreDotFiles()
    {
        $finder = $this->buildFinder();
        $this->assertSame($finder, $finder->ignoreDotFiles(false)->ignoreVCS(false));
        $this->assertIterator($this->toAbsolute([
            '.git',
            '.bar',
            '.foo',
            '.foo/.bar',
            '.foo/bar',
            'foo',
            'foo/bar.tmp',
            'test.php',
            'test.py',
            'toto',
            'toto/.git',
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

        $finder = $this->buildFinder();
        $finder->ignoreDotFiles(false)->ignoreDotFiles(false)->ignoreVCS(false);
        $this->assertIterator($this->toAbsolute([
            '.git',
            '.bar',
            '.foo',
            '.foo/.bar',
            '.foo/bar',
            'foo',
            'foo/bar.tmp',
            'test.php',
            'test.py',
            'toto',
            'toto/.git',
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

        $finder = $this->buildFinder();
        $this->assertSame($finder, $finder->ignoreDotFiles(true)->ignoreVCS(false));
        $this->assertIterator($this->toAbsolute([
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

    public function testIgnoreDotFilesCanBeDisabledAfterFirstIteration()
    {
        $finder = $this->buildFinder();
        $finder->in(self::$tmpDir);

        $this->assertIterator($this->toAbsolute([
            'foo',
            'foo/bar.tmp',
            'qux',
            'qux/baz_100_1.py',
            'qux/baz_1_2.py',
            'qux_0_1.php',
            'qux_1000_1.php',
            'qux_1002_0.php',
            'qux_10_2.php',
            'qux_12_0.php',
            'qux_2_0.php',
            'test.php',
            'test.py',
            'toto',
            'foo bar',
        ]), $finder->getIterator());

        $finder->ignoreDotFiles(false);
        $this->assertIterator($this->toAbsolute([
            'foo',
            'foo/bar.tmp',
            'qux',
            'qux/baz_100_1.py',
            'qux/baz_1_2.py',
            'qux_0_1.php',
            'qux_1000_1.php',
            'qux_1002_0.php',
            'qux_10_2.php',
            'qux_12_0.php',
            'qux_2_0.php',
            'test.php',
            'test.py',
            'toto',
            '.bar',
            '.foo',
            '.foo/.bar',
            '.foo/bar',
            'foo bar',
        ]), $finder->getIterator());
    }

    public function testSortByName()
    {
        $finder = $this->buildFinder();
        $this->assertSame($finder, $finder->sortByName());
        $this->assertIterator($this->toAbsolute([
            'foo',
            'foo bar',
            'foo/bar.tmp',
            'qux',
            'qux/baz_100_1.py',
            'qux/baz_1_2.py',
            'qux_0_1.php',
            'qux_1000_1.php',
            'qux_1002_0.php',
            'qux_10_2.php',
            'qux_12_0.php',
            'qux_2_0.php',
       