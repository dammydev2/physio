 );
        $this->assertEquals(
            'something',
            $this->mock->getElement()->getFirst()
        );
    }

    public function testDemeterChain()
    {
        $this->mock->shouldReceive('getElement->getFirst')
            ->once()
            ->andReturn('somethingElse');

        $this->assertEquals('somethingElse', $this->mock->getElement()->getFirst());
    }

    public function testMultiLevelDemeterChain()
    {
        $this->mock->shouldReceive('levelOne->levelTwo->getFirst')
            ->andReturn('first');

        $this->mock->shouldReceive('levelOne->levelTwo->getSecond')
            ->andReturn('second');

        $this->assertEquals(
            'second',
            $this->mock->levelOne()->levelTwo()->getSecond()
        );
        $this->assertEquals(
            'first',
            $this->mock->levelOne()->levelTwo()->getFirst()
        );
    }

    public function testSimilarDemeterChainsOnDifferentClasses()
    {
        $mock1 = Mockery::mock('overload:mock1');
        $mock1->shouldReceive('select->some->data')->andReturn(1);
        $mock1->shouldReceive('select->some->other->data')->andReturn(2);

        $mock2 = Mockery::mock('overload:mock2');
        $mock2->shouldReceive('select->some->data')->andReturn(3);
        $mock2->shouldReceive('select->some->other->data')->andReturn(4);

        $this->assertEquals(1, mock1::select()->some()->data());
        $this->assertEquals(2, mock1::select