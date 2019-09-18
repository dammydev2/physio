<?php

namespace test\Mockery;

use Mockery\Adapter\Phpunit\MockeryTestCase;

class MockClassWithMethodOverloadingTest extends MockeryTestCase
{
    public function testCreateMockForClassWithMethodOverloading()
    {
        $mock = mock('test\Mockery\TestWithMethodOverloading')
            ->makePartial();
        $this->assertInstanceOf('test\Mockery\TestWithMethodOverloading', $mock);

        $this->expectException(\BadMethodCallException::class);

        // TestWithMethodOverloading::__call wouldn't be used. See Gotchas!.
        $mock->randomMethod();
    }

    public function testCreateMockForClassWithMethodOverloadingWithExistingMethod()
    {
        $mock = mock('test\Mockery\TestWithMethodOv