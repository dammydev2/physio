              "a\np\nc\nd\ne\nf\ng\nh\ni\nw\nk\n",
            ],
            [
                <<<EOF
--- Original
+++ New
@@ @@
-A
+B
 1
 2
 3

EOF
            ,
                "A\n1\n2\n3\n4\n5\n6\n1\n1\n1\n1\n1\n1\n1\n1\n1\n1\n1\n1\n1\n1\n1\n",
                "B\n1\n2\n3\n4\n5\n6\n1\n1\n1\n1\n1\n1\n1\n1\n1\n1\n1\n1\n1\n1\n1\n",
            ],
            [
                "--- Original\n+++ New\n@@ @@\n #Warning: Strings contain different line endings!\n-<?php\r\n+<?php\n A\n",
                "<?php\r\nA\n",
                "<?php\nA\n",
            ],
            [
                "--- Original\n+++ New\n@@ @@\n #Warning: Strings contain different line endings!\n-a\r\n+\n+c\r\n",
                "a\r\n",
                "\nc\r",
            ],
        ];
    }

    public function testDiffToArrayInvalidFromType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('#^"from" must be an array or string\.$#');

        $this->differ->diffToArray(null, '');
    }

    public function testDiffInvalidToType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('#^"to" must be an array or string\.$#');

        $this->differ->diffToArray('', new \stdClass);
    }

    /**
     * @param array  $expected
     * @param string $input
     *
     * @dataProvider provideSplitStringByLinesCases
     */
    public function testSplitStringByLines(array $expected, string $input): void
    {
        $reflection = new \ReflectionObject($this->differ);
        $method     = $reflection->getMethod('splitStringByLines');
        $method->setAccessible(true);

        $this->assertSame($expected, $method->invoke($this->differ, $input));
    }

    public function provideSplitStringByLinesCases(): array
    {
        return [
            [
                [],
                '',
            ],
            [
                ['a'],
                'a',
            ],
            [
                ["a\n"],
                "a\n",
            ],
            [
                ["a\r"],
                "a\r",
            ],
            [
                ["a\r\n"],
                "a\r\n",
            ],
            [
                ["\n"],
                "\n",
            ],
            [
                ["\r"],
                "\r",
            ],
            [
                ["\r\n"],
                "\r\n",
            ],
            [
                [
                    "A\n",
                    "B\n",
                    "\n",
                    "C\n",
                ],
                "A\nB\n\nC\n",
            ],
            [
                [
                    "A\r\n",
                    "B\n",
                    "\n",
                    "C\r",
                ],
                "A\r\nB\n\nC\r",
            ],
            [
                [
                    "\n",
                    "A\r\n",
                    "B\n",
                    "\n",
                    'C',
                ],
                "\nA\r\nB\n\nC",
            ],
        ];
    }

    public function testConstructorInvalidArgInt(): void
    {
        $