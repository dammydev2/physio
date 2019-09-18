ds Foo
     * @depends ほげ
     *
     * @todo Remove fixture from test class
     */
    public function methodForTestParseAnnotation(): void
    {
    }

    public function testParseAnnotationThatIsOnlyOneLine(): void
    {
        $this->assertEquals(
            ['Bar'],
            Test::getDependencies(\get_class($this), 'methodForTestParseAnnotationThatIsOnlyOneLine')
        );
    }

    /** @depends Bar */
    public function methodForTestParseAnnotationThatIsOnlyOneLine(): void
    {
        // TODO Remove fixture from test class
    }

    /**
     * @dataProvider getLinesToBeCoveredProvider
     *
     * @throws CodeCoverageException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetLinesToBeCovered($test, $lines): void
    {
        if (\strpos($test, 'Namespace') === 0) {
            $expected = [
                TEST_FILES_PATH . 'NamespaceCoveredClass.php' => $lines,
            ];
        } elseif ($test === 'CoverageCoversOverridesCoversNothingTest') {
            $expected = [TEST_FILES_PATH . 'CoveredClass.php' => $lines];
        } elseif ($test === 'CoverageNoneTest') {
            $expected = [];
        } elseif ($test === 'CoverageNothingTest') {
            $expected = false;
        } elseif ($test === 'CoverageFunctionTest') {
            $expected = [
                TEST_FILES_PATH . 'CoveredFunction.php' => $lines,
            ];
        } else {
            $expected = [TEST_FILES_PATH . 'CoveredClass.php' => $lines];
        }

        $this->assertEquals(
            $expected,
            Test::getLinesToBeCovered(
                $test,
                'testSomething'
            )
        );
    }

    public function testGetLinesToBeCovered2(): void
    {
        $this->expectException(CodeCoverageException::class);

        Test::getLinesToBeCovered(
            'NotExistingCoveredElementTest',
            'testOne'
        );
    }

    public function testGetLinesToBeCovered3(): void
    {
        $this->expectException(CodeCoverageException::class);

        Test::getLinesToBeCovered(
            'NotExistingCoveredElementTest',
            'testTwo'
        );
    }

    public function testGetLinesToBeCovered4(): void
    {
        $this->expectException(CodeCoverageException::class);

        Test::getLinesToBeCovered(
            'NotExistingCoveredElementTest',
            'testThree'
        );
    }

    public function testGetLinesToBeCoveredSkipsNonExistentMethods(): void
    {
        $this->assertSame(
            [],
            Test::getLinesToBeCovered(
                'NotExistingCoveredElementTest',
                'methodDoesNotExist'
            )
        );
    }

   