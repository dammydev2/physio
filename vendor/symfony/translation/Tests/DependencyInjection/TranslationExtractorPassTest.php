  {
        return [
            ['foo', 3, '{1,2, 3 ,4}'],
            ['bar', 10, '{1,2, 3 ,4}'],
            ['bar', 3, '[1,2]'],
            ['foo', 1, '[1,2]'],
            ['foo', 2, '[1,2]'],
            ['bar', 1, ']1,2['],
            ['bar', 2, ']1,2['],
            ['foo', log(0), '[-Inf,2['],
            ['foo', -log(0), '[-2,+Inf]'],
        ];
    }

    /**
     * @dataProvider getChooseTests
     */
    public function testChoose($expected, $id, $number)
    {
        $translator = $this->getTranslator();

        $this->assertEquals($expected, $translator->trans($id, ['%count%' => $number]));
    }

    public function testReturnMessageIfExactlyOneStandardRuleIsGiven()
    {
        $translator = $this->getTranslator();

        $this->assertEquals('There are two apples', $translator->trans('There are two apples', ['%count%' => 2]));
    }

    /**
     * @dataProvider getNonMatchingMessages
     * @expectedException \InvalidArgumentException
     */
    public function testThrowExceptionIfMatchingMessageCannotBeFound($id, $number)
    {
        $translator = $this->getTranslator();

        $translator->trans($id, ['%count%' => $number]);
    }

    public function getNonMatchingMessages()
    {
        return [
            ['{0} There are no apples|{1} There is one apple', 2],
            ['{1} There is one apple|]1,Inf] There are %count% apples', 0],
            ['{1} There is one apple|]2,Inf] There are %count% apples', 2],
            ['{0} There are no apples|There is one apple', 2],
        ];
    }

    public function getChooseTests()
    {
        return [
            ['There are no apples', '{0} There are no apples|{1} There is one apple|]1,Inf] There are %count% apples', 0],
            ['There are no apples', '{0}     There are no apples|{1} There is one apple|]1,Inf] There are %count% apples', 0],
            ['There are no apples', '{0}There are no apples|{1} There is one apple|]1,Inf] There are %count% apples', 0],

            ['There is one apple', '{0} There are no apples|{1} There is one apple|]1,Inf] There are %count% apples', 1],

            ['There are 10 apples', '{0} There are no apples|{1} There is one apple|]1,Inf] There are %count% apples', 10],
            ['There are 10 apples', '{0} There are no apples|{1} There is one apple|]1,Inf]There are %count% apples', 10],
            ['There are 10 apples', '{0} There are no apples|{1} There is one apple|]1,Inf]     There are %count% apples', 10],

            [