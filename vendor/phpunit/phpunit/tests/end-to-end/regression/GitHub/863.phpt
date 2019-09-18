aluate(new \ClassWithNonPublicAttributes, '', true));
        $this->assertEquals('does not have attribute "privateAttribute"', $constraint->toString());
        $this->assertCount(1, $constraint);

        try {
            $constraint->evaluate(new \ClassWithNonPublicAttributes);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<EOF
Failed asserting that object of class "ClassWithNonPublicAttributes" does not have attribute "privateAttribute".

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    public function testConstraintObjectNotHasAttribute2(): void
    {
        $constraint = Assert::logicalNot(
            Asser