, '*'));
        $this->assertTrue($f->isSatisfied('12', '12'));
        $this->assertFalse($f->isSatisfied('12', '3-11'));
        $this->assertFalse($f->isSatisfied('12', '3-7/2'));
        $this->assertFalse($f->isSatisfied('12', '11'));
    }

    /**
     * Allows ranges and lists to coexist in the same expression
     *
     * @see https://github.com/dragonmantank/cron-expression/issues/5
     */
    public function testAllowRangesAndLists()
    {
        $expression = '5-7,11-13';
        $f = new HoursField();
        $this->assertTrue($f->validate($expression));
    }

    /**
     * Makes sure that various types of ranges expand out properly
     *
     * @see https://github.com/dragonmantank/cron-expression/issues/5
     */
    public function testGetRangeForExpressionExpandsCorrectly()
    {
        $f = new HoursField();
        $this->assertSame([5, 6, 7, 11, 12, 13], $f->getRangeForExpress