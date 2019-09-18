       $this->assertEquals(1, $result->skippedCount());
        $this->assertEquals(
            'Operating system matching /DOESNOTEXIST/i is required.',
            $test->getStatusMessage()
        );
    }

    public function testSkipsIfRequiresNonExistingOsFamily(): void
    {
        $test   = new \RequirementsTest('testAlwaysSkip4');
        $result = $test->run();

        $this->assertEquals(1, $result->skippedCount());
        $this->assertEquals(
            'Operating system DOESNOTEXIST is required.',
            $test->getStatusMessage()
        );
    }

    public function testSkipsIfRequiresNonExistingFunction(): void
    {
        $test   = new \RequirementsTest('testNine');
        $result = $test->run();

        $this->assertEquals(1, $result->skippedCount());
        $this->assertEquals(
            'Function testFunc is required.',
  