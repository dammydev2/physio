'abc',
            ],
            [
                [
                    ['bc', Differ::REMOVED],
                    ['abc', Differ::ADDED],
                ],
                'bc',
                'abc',
            ],
            [
                [
                    ['abc', Differ::REMOVED],
                    ['abbc', Differ::ADDED],
                ],
                'abc',
                'abbc',
            ],
            [
                [
                    ['abcdde', Differ::REMOVED],
                    ['abcde', Differ::ADDED],
                ],
                'abcdde',
                'abcde',
            ],
            'same start' => [
                [
                    [17, Differ::OLD],
                    ['b', Differ::REMOVED],
                    ['d', Differ::ADDED],
                ],
                [30 => 17, 'a' => 'b'],
                [30 => 17, 'c' => 'd'],
            ],
            'same end' => [
                [
                    [1, Differ::REMOVED],
                    [2, Differ::ADDED],
                    ['b', Differ::OLD],
                ],
                [1 => 1, 'a' => 'b'],
                [1 => 2, 'a' => 'b'],
            ],
            'same start (2), same end (1)' => [
                [
                    [17, Differ::OLD],
                    [2, Differ::OLD],
                    [4, Differ::REMOVED],
                    ['a', Differ::ADDED],
                    [5, Differ::ADDED],
                    ['x', Differ::OLD],
                ],
                [30 => 17, 1 => 2, 2 => 4, 'z' => 'x'