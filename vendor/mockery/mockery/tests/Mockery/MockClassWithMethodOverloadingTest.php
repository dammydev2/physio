uldMockClassWithHintedParamsInMagicMethod()
    {
        $this->assertNotNull(
            \Mockery::mock('test\Mockery\MagicParams')
        );
    }

    public function testItShouldMockClassWithHintedReturnInMagicMethod()
    {
        $this->assertNotNull(
            \Mockery::mock('test\Mockery\MagicReturns')
        );
    }
}

class MagicParams
{
    public function __isset(string $property)
    {
        return false;
    }
}

class MagicReturns
{
    public function __isset($property) : bool
    {
        return false;
    }
}

abstract class TestWithParameterAndReturnType
{
    public function returnString(): string {}

    public function returnInteger(): int {}

    public function returnFloat(): float {}

    public function returnBoolean(): bool {}

    public function returnArray(): array {}

    public function returnCallable(): callable {}

    public function returnGenerator(): \Generator {}

    public function withClassReturnType(): TestWithParameterAndReturnType {}

    public function withScalarParameters(int $integer, float $float, bool $boolea