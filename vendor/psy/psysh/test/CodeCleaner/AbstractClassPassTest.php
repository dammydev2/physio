een arguments',
            ],
            [
                '"quoted"',
                [['quoted', '"quoted"']],
                '->tokenize() parses quoted arguments',
            ],
            [
                "'quoted'",
                [['quoted', "'quoted'"]],
                '->tokenize() parses quoted arguments',
            ],
            [
                "'a\rb\nc\td'",
                [["a\rb\nc\td", "'a\rb\nc\td'"]],
                '->tokenize() parses whitespace chars in strings',
            ],
            [
                "'a'\r'b'\n'c'\t'd'",
                [
                    ['a', "'a'\r'b'\n'c'\t'd'"],
                    ['b', "'b'\n'c'\t'd'"],
                    ['c', "'c'\t'd'"],
                    ['d', "'d'"],
                ],
                '->tokenize() parses whitespace chars between args as spaces',
            ],

            /*
             * These don't play nice with unescaping input, but the end result
             * is correct, so disable the tests for now.
             *
             * @todo Sort this out and re-enable these test cases.
             */
            // [
            //     '\"quoted\"',
            //     [['"quoted"', '\"quoted\"']],
            //     '->tokenize() parses escaped-quoted arguments',
            // ],
            // [