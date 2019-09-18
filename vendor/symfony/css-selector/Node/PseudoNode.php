            ['e.warning', "e[@class and contains(concat(' ', normalize-space(@class), ' '), ' warning ')]"],
            ['e#myid', "e[@id = 'myid']"],
            ['e:not(:nth-child(odd))', 'e[not(position() - 1 >= 0 and (position() - 1) mod 2 = 0)]'],
            ['e:nOT(*)', 'e[0]'],
            ['e f', 'e/descendant-or-self::*/f'],
            ['e > f', 'e/f'],
            ['e + f', "e/following-sibling::*[(name() = 'f') and (position() = 1)]"],
            ['e ~ f', 'e/following-sibling::f'],
            ['div#container p', "div[@id = 'container']/descendant-or-self::*/p"],
        ];
    }

    public function getXmlLangTestData()
    {
        return [
            [':lang("EN")', ['first', 'second', 'third', 'fourth']],
            [':lang("en-us")', ['second', 'fourth']],
            [':lang(en-nz)', ['third']],
            [':lang(fr)', ['fifth']],
            [':lang(ru)', ['sixth']],
            [":lang('ZH')", ['eighth']],
            [':lang(de) :lang(zh)', ['eighth']],
            [':lang(en), :lang(zh)', ['first', 'second', 'third', 'fourth', 'eighth']],
            [':lang(es)', []],
        ];
    }

    public function getHtmlIdsTestData()
    {
        return [
            ['div', ['outer-div', 'li-div', 'foobar-div']],
            ['DIV', ['outer-div', 'li-div', 'foobar-div']],  // case-insensitive in HTML
            ['div div', ['li-div']],
           