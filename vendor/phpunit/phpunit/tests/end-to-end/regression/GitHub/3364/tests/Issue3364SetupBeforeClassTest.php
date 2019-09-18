stWithTest::class);

        \BeforeAndAfterTest::resetProperties();
        $result = $test->run();

        $this->assertCount(4, $result->passed());
    }

    public function testSkippedTestDataProvider(): void
    {
        $suite = new TestSuite(\DataProviderSkippedTest::class);

        $suite->run($this->result);

        $this->assertEquals(3, $this->result->count());
        $this->assertEquals(1, $this->result->skippedCount());
    }

    public function testTestDataProviderDependency(): void
    {
        $suite = new TestSuite(\DataProviderDependencyTest::class);

        $suite->run($this->result);

        $skipped           = $this->result->skipped()