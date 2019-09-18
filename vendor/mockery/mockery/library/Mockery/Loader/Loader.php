>with($this->matchesRegularExpression('/^http:/'), $this->isType('string'))
            ->will($this->returnValue(true));

        $this->assertTrue($service->login('user', 'pass'));
        $this->assertFalse($service->hasBookmarksTagged('php'));
        $this->assertTrue($service->addBookmark('http://example.com/1', 'some_tag1'));
        $this->assertTrue($service->addBookmark('http://example.com/2', 'some_tag2'));
        $this->assertTrue($service->addBookmark('http://example.com/3', 'some_tag3'));
        $this->assertTrue($service->hasBookmarksTagged('php'));
        $this->assertTrue($service->hasBookmarksTagged('php'));
    }

    public function testMockedMethodsCallableFromWithinOriginalClass()
    {
        $mock = mock('MockeryTest_InterMethod1[doThird]');
        $mock->shouldReceive('doThird')->and