ionFailedError::class);

        $this->assertObjectNotHasAttribute('privateAttribute', $obj);
    }

    public function testAssertThatAttributeEquals(): void
    {
        $this->assertThat(
            new \ClassWithNonPublicAttributes,
            $this->attribute(
                $this->equalTo('foo'),
                'publicAttribute'
            )
        );
    }

    public function testAssertThatAttributeEquals2(): void
    {
        $this->expectException(AssertionFailedError::class);

        $this->assertThat(
            new \ClassWithNonPublicAttributes,
            $this->attribute(
                $this->equalTo('bar'),
                'publicAttribute'
            )
        );
    }

    public function testAssertThatAttributeEqualTo(): void
    {
        $this->assertThat(
            new \ClassWithNonPublicAttributes,
            $this->attributeEqualTo('publicAttribute', 'foo')
        );
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testAssertThatAnything(): void
    {
        $this->assertThat('anything', $this->anythi