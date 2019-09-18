his->once())
             ->method('right');

        $mock->wrong();

        try {
            $mock->__phpunit_verify();
            $this->fail('Expected exception');
        } catch (ExpectationFailedException $e) {
            $this->assertSame(
                "Expectation failed for method name is equal to 'right' when invoked 1 time(s).\n" .
                'Method was expected to be called 1 times, actually called 0 times.' . "\n",
                $e->getMessage()
            );
        }

        $this->resetMockObjects();
    }

    public function testVerificationOfMethodNameFailsWithParameters(): void
    {
        $mock = $this->getMockBuilder(SomeClass::class)
                     ->setMethods(['right', 'wrong'])
                     ->getMock();

        $mock->expects($this->once())
             ->method('right');

        $mock->wrong();

        try {
            $mock->__phpunit_verify();
            $this->fail('Expected exception');
        } catch (ExpectationFailedException $e) {
            $this->assertSame(
                "Expectation failed for method name is equal to 'right' when invoked 1 time(s).\n" .
                'Method was expected to be called 1 times, actually called 0 times.' . "\n",
                $e->getMessage()
            );
        }

        $this->resetMockObjects();
    }

    public function testVerificationOfMethodNameFailsWithWrongParameters(): void
    {
        $mock = $this->getMockBuilder(SomeClass::class)
                     ->setMethods(['right', 'wrong'])
                     ->getMock();

        $mock->expects($this->once())
             ->method('right')
             ->with(['first', 'second']);

        try {
            $mock->right(['second']);
        } catch (ExpectationFailedException $e) {
            $this->assertSame(
                "Expectation failed for method name is equal to 'right' when invoked 1 time(s)\n" .
                'Parameter 0 for invocation SomeClass::right(Array (...)) does not match expected value.' . "\n" .
                'Failed asserting that two arrays are equal.',
                $e->getMessage()
            );
        }

        try {
            $mock->__phpunit_verify();

            // CHECKOUT THIS MORE CAREFULLY
//            $this->fail('Expected exception');
        } catch (ExpectationFailedException $e) {
            $this->assertSame(
                "Expectation failed for method name is equal to 'right' when invoked 1 time(s).\n" .
                'Parameter 0 for invocation SomeClass::right(Array (...)) does not match expected value.' . "\n" .
                'Failed asserting that two arrays are equal.' . "\n" .
                '--- Expected' . "\n" .
                '+++ Actual' . "\n" .
                '@@ @@' . "\n" .
                ' Array (' . "\n" .
                '-    0 => \'first\'' . "\n" .
                '-    1 => \'second\'' . "\n" .
                '+    0 => \'second\'' . "\n" .
                ' )' . "\n",
                $e->getMessage()
            );
        }

        $this->resetMockObjects();
    }

    public function testVerificationOfNeverFailsWithEmpt