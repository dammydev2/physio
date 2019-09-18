->assertTrue($this->concreteLexer->moveNext());

        $this->assertEquals(
            array(
                'value' => "\xE9=10",
                'type' => 'string',
                'position' => 0,
            ),
            $this->concreteLexer->lookahead
        );
    }

    /**
     * @dataProvider dataProvider
     *
     * @param $input
     * @param $expectedTokens
     */
    public function testPeek($input, $expectedTokens)
    {
        $this->concreteLexer->setInput($input);
        foreach ($expectedTokens as $expectedToken) {
            $this->assertEquals($expectedToken, $this->concreteLexer->peek());
        }

        $this->assertNull($this->concreteLexer->peek());
    }

    /**
     * @dataProvider dataProvider
     *
     * @param $input
     * @param $expectedTokens
     */
    public function testGlimpse($input, $expectedTokens)
    {
        $this->concreteLexer->setInput($input);

        foreach ($expectedTokens as $expectedToken) {
            $this->assertEquals($expectedToken, $this->concreteLexer->glimpse());
            $this->concreteLexer->moveNext();
        }

        $this->assertNull($this->concreteLexer->peek());
    }

    public function inputUntilPositionDataProvider()
    {
        return array(
            array('price=10', 5, 'price'),
        );
    }

    /**
     * @dataProvider inputUntilPositionDataProvider
     *
     * @param $input
     * @param $position
     * @param $expectedInput
     */
    public function testGetInputUntilPosition($input, $position, $expectedInput)
    {
        $this->concreteLexer->setInput($input);

        $this->assertSame($expectedInput, $this->concreteLexer->getInputUntilPosition($position));
    }

    /**
     * @dataProvider dataProvider
     *
     * @param $input
     * @param $expectedTokens
     */
    public function testIsNextToken($input, $expectedTokens)
    {
        $this->concreteLexer->setInput($input);

        $this->concreteLexer->moveNext();
        for ($i = 0; $i < coun