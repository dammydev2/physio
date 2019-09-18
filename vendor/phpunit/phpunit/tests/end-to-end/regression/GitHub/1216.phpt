taticAttributeRaisesExceptionForInvalidFirstArgument2(): void
    {
        $this->expectException(Exception::class);

        $this->getStaticAttribute('NotExistingClass', 'foo');
    }

    public function testGetStaticAttributeRaisesExceptionForInvalidSecondArgument2(): void
    {
        $this->expectException(Exception::class);

        $this->getStaticAttribute(\stdClass::class, '0');
    }

    public function testGetStaticAttributeRaisesExceptionForInvalidSecondArgument3(): void
    {
        $this->expectException(Exception::class);

        $this->getStaticAttribute(\stdClass::class, 'foo');
    }

    public function testGetObjectAttributeRaisesExceptionForInvalidFirstArgument(): void
    {
        $thi