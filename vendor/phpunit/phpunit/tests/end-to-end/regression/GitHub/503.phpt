() is an instance of class "Exception".

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    public function testConstraintIsInstanceOf2(): void
    {
        $constraint = Assert::isInstanceOf(\Exception::class);

        try {
            $constraint->evaluate(new \stdClass, 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<EOF
custom message
Failed asserting that stdClass Object () is an instance of class "Exception".

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
  