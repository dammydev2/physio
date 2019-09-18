rror occurred' contains 'A logic error occurred'.",
            $test->getStatusMessage()
        );
    }

    public function testExceptionWithRegexpMessage(): void
    {
        $test = new \ThrowExceptionTestCase('test');
        $test->expectException(\RuntimeException::class);
        $test->expectExceptionMessageRegExp('/runtime .*? occurred/');

        $result = $test->run();

        $this->assertCount(1, $result);
        $this->assertTrue($result->wasSuccessful());
    }

    public function testExceptionWithWrongRegexpMessage(): void
    {
        $test = new \ThrowExceptionTestCase('test');
        $test->expectException(\RuntimeException::class);
        $test->expectExceptionMessageRegExp('/logic .*? occu