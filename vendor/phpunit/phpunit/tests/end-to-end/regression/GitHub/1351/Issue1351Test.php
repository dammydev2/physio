xpectationFailedException $e) {
            $this->assertEquals(
                <<<EOF
Failed asserting that actual size 2 matches expected size 5.

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    public function testConstraintNotCountFailing(): void
    {
        $constraint = Assert::logicalNot(
            new Count(2)
        );

        try {
            $constraint->evaluate([1, 2]);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<EOF
Failed asserting that actual size 2 does not match expected size 2.

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    public function testConstraintNotSameSizeFailing(): void
    {
        $constraint = Assert::logicalNot(
            new SameSize([1, 2])
        );

        try {
            $constraint->evaluate([3, 4]);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<EOF
Failed asserting that actual size 2 does not match expected size 2.

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    public function testConstraintException(): void
    {
        $constraint = new Constraint\Exception('FoobarException');
        $exception  = new \DummyException('Test');
        $stackTrace = Filter::getFilteredStacktrace($exception);

        try {
            $constraint->evaluate($exception);
        } catch (ExpectationFailedException $e) {
            $this->as