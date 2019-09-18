not null.

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    public function testConstraintIsNotNull2(): void
    {
        $constraint = Assert::logicalNot(
            Assert::isNull()
        );

        try {
            $constraint->evaluate(null, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<EOF
custom message
Failed asserting that null is not null.

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    public function testConstraintNotLessThan(): void
    {
        $constraint = Assert::logicalNot(
            Ass