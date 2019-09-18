            return;
        }

        $this->fail();
    }

    public function testConstraintArrayNotContains(): void
    {
        $constraint = Assert::logicalNot(
            new TraversableContains('foo')
        );

        $this->assertTrue($constraint->evaluate(['bar'], '', true));
        $this->assertFalse($constraint->evaluate(['foo'], '', true));
        $this->assertEquals("does not contain 'foo'", $constraint->toString());
        $this->assertCount(1, $constraint);

        try {
            $constraint->evaluate(['foo']);
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<EOF
Failed asserting that an array does not contain 'foo'.

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    public function testConstraintArrayNotContains2(): void
    {
        $constraint = Assert::logicalNot(
            new TraversableContains('foo')
        );

        try {
            $constraint->evaluate(['foo'], 'custom message');
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                <<<EOF
custom message
Failed asserting that an array does not contain 'foo'.

EOF
                ,
                TestFailure::exceptionToString($e)
            );

            return;
        }

        $this->fail();
    }

    public function testAttributeNotEqualTo(): void
    {
        $object     = new \ClassWithNonPublicAttributes;
        $constraint = Assert::logicalNot(
            Assert::attributeEqualTo('foo', 2)
        );

        $this->assertTrue($constraint->evaluate($object, '', true));
        $this->assertEquals('attribute "foo" is not equal to 2', $constraint->toString());
        $this->assertCount(1, $constraint);

        $constraint = Assert::logicalNot(
            Assert::attributeEqualTo('foo', 1)
        );

        $this->assertFalse($constrain