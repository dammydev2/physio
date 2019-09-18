0, '', true));
        $this->assertFalse($constraint->evaluate(1, '', true));
        $this->assertEquals('is not equal to 1', $constraint->toString());
        $this->assertCount(1, $constraint);

        try {
            $constraint->evaluate(1);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<EOF
Failed asserting that 1 is not equal to 1.

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    public function testConstraintIsNotEqual2(): void
    {
        $constraint = Assert::logicalNot(
            Assert::equalTo(1)
        );

        try {
            $constraint->evaluate(1, 'custom message');
