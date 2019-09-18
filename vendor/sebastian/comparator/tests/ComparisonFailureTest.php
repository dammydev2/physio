te($from, $to);

            $this->assertSame($to, $common);
        }
    }

    public function testSingleElementSubsequenceAtMiddle(): void
    {
        foreach ($this->stress_sizes as $size) {
            $from   = \range(1, $size);
            $to     = \array_slice($from, (int) ($size / 2), 1);
            $common = $this->implementation->calculate($from, $to);

            $this->assertSame($to, $common);
        }
    }

    public function testSingleElementSubsequenceAtEnd(): void
    {
        foreach ($this->stress_sizes as $size) {
            $from   = \range(1, $size);
            $to     = \array_slice($from, $size - 1, 1);
            $common = $this->implementation->calculate($from, $to);

            $this->assertSame($to, $common);
        }
    }

    public function testReversedSequences(): void
    {
        $from     = ['A', 'B'];
        $to       = ['B', 'A'];
        $expected = ['A'];
        $common   = $this->implementation->calculate($from, $to);
        $this->assertSame($expected, $common);

        foreach ($this->stress_sizes as $size) {
            $from   = \range(1, $size);
            $to     = \array_reverse($from);
            $common = $this->implementation->calculate($from, $to);

            $this->assertSame([1], $common);
        }
    }

    public function testStrictTypeCalculate(): void
    {
        $diff = $this->implementation->calculate(['5'], ['05']);

        $this->assertIsArray($diff);
        $this->assertCount(0, $diff);
    }

    /**
     * @return LongestC