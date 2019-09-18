        $paths[] = $file->getRelativePathname();
        }

        $ref = [
            'test.php',
            'toto',
            'test.py',
            'foo',
            'foo'.\DIRECTORY_SEPARATOR.'bar.tmp',
            'foo bar',
            'qux',
            'qux'.\DIRECTORY_SEPARATOR.'baz_100_1.py',
            'qux'.\DIRECTORY_SEPARATOR.'baz_1_2.py',
            'qux_0_1.php',
            'qux_1000_1.php',
            'qux_1002_0.php',
            'qux_10_2.php',
            'qux_12_0.php',
            'qux_2_0.php',
        ];

        sort($paths);
        sort($ref);

        $this->assertEquals($ref, $paths);
    }

    public function testAppendWithAFinder()
    {
        $finder = $this->buildFinder();
        $finder->files()->in(self::$tmpDir.\DIRECTORY_SEPARATOR.'foo');

        $finder1 = $this->buildFinder();
        $finder1->directories()->in(self::$tmpDir);

        $finder = $finder->append($finder1);

        $this->assertIterator($this->toAbsolute(['foo', 'foo/bar.tmp', 'qux', 'toto']), $finder->getIterator());
    }

    public function testAppendWithAnArray()
    {
        $finder = $this->buildFinder();
        $finder->files()->in(self::$tmpDir.\DIRECTORY_SEPARATOR.'foo');

        $finder->append($this->toAbsolute(['foo', 'toto']));

        $this->assertIterator($this->toAbsolute(['foo', 'foo/bar.tmp', 'toto']), $finder->getIterator());
    }

    public function testAppendReturnsAFinder()
    {
        $this->assertInstanceOf('Symfony\\Component\\Finder\\Finder', Finder::create()->append([]));
    }

    public function testAppendDoesNotRequireIn()
    {
        $finder = $this->buildFinder();
        $finder->in(self::$tmpDir.\DIRECTORY_SEPARATOR.'foo');

        $finder1 = Finder::create()->append($finder);

        $this->assertIterator(iterator_to_array($finder->getIterator()), $finder1->getIterator());
    }

    public function testCountDirectories()
    {
        $directory = Finder::create()->directories()->in(self::$tmpDir);
        $i = 0;

        foreach ($directory as $dir) {
            ++$i;
        }

        $this->assertCount($i, $directory);
    }

    public function testCountFiles()
    {
        $files = Finder::create()->files()->in(__DIR__.\DIRECTORY_SEPARATOR.'Fixtures');
        $i = 0;

        foreach ($files as $file) {
            ++$i;
        }

        $this->assertCount($i, $files);
    }

    /**
     * @expectedException \LogicException
     */
    public function testCountWithoutIn()
    {
        $finder = Finder::create()->files();
        \count($finder);
    }

    public function testHasResults()
    {
        $finder = $this->buildFinder();
        $finder->in(__DIR__);
        $this->assertTrue($finder->hasResults());
    }

    public function testNoResults()
    {
        $finder = $this->buildFinder();
        $finder->in(__DIR__)->name('DoesNotExist');
        $this->assertFalse($finder->hasResults());
    }

    /**
     * @dataProvider getContainsTestData
     */
    public function testContains($matchPatterns, $noMatchPatterns, $expected)
    {
        $finder = $this->buildFinder();
        $finder->in(__DIR__.\DIRECTORY_SEPARATOR.'Fixtures')
            ->name('*.txt')->sortByName()
            ->contains($matchPatterns)
            ->notContains($noMatchPatterns);

        $this->assertIterator($this->toAbsoluteFixtures($expected), $finder);
    }

    public function testContainsOnDirectory()
    {
        $finder = $this->buildFinder();
        $finder->in(__DIR__)
            ->directories()
            ->name('Fixtures')
            ->contains('abc');
        $this->assertIterator([], $finder);
    }

    public function testNotContainsOnDirectory()
    {
        $finder = $this->buildFinder();
        $finder->in(__DIR__)
            ->directories()
            ->name('Fixtures')
            ->notContains('abc');
        $this->assertIterator([], $finder);
    }

    /**
     * Searching in multiple locations involves AppendIterator which does an unnecessary rewind which leaves FilterIterator
     * with inner FilesystemIterator in an invalid state.
     *
     * @see https://bugs.php.net/68557
     */
    public function testMultipleLocations()
    {
        $locations = [
            self::$tmpDir.'/',
            self::$tmpDir.'/toto/',
        ];

        // it is expected that there are test.py test.php in the tmpDir
        $finder = new Finder();
        $finder->in($locations)
            // the default flag IGNORE_DOT_FILES fixes the problem indirectly
            // so we set it to false for better isolation
            ->ignoreDotFiles(false)
            ->depth('< 1')->name('test.php');

        $this->assertCount(1, $finder);
    }

    /**
     * Searching in multiple locations with sub directories involves
     * AppendIterator which does an unnecessary rewind which leaves
     * FilterIterator with inner FilesystemIterator in an invalid state.
     *
     * @see https://bugs.php.net/68557
     */
    public function testMultipleLocationsWithSubDirectories()
    {
        $locations = [
            __DIR__.'/Fixtures/one',
            self::$tmpDir.\DIRECTORY_SEPARATOR.'toto',
        ];

        $finder = $this->buildFinder();
        $finder->in($locations)->depth('< 10')->name('*.neon');

        $expected = [
            __DIR_