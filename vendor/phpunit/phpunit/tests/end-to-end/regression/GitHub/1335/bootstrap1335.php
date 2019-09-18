

            return;
        }

        $this->fail();
    }

    public function testConstraintStringNotContains(): void
    {
        $constraint = Assert::logicalNot(
            Assert::stringContains('foo')
        );

        $this->assertTrue($constraint->evaluate('barbazbar', '', true));
        $this->assertFalse($constraint->evaluate('barfoobar', '', true));
        $this->assertEquals('does not contain "foo"', $constraint->toString());
        $this->assertCount(1, $constraint);

        try {
            $constraint->evaluate('barfoobar');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<EOF
Failed asserting that 'barfoobar' does not contain "foo".

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

   