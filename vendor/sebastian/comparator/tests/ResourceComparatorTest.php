oFile'     => __FILE__,
                    'toFileDate' => $time,
                ],
                'Option "toFileDate" must be a string or <null>, got "integer#' . $time . '".',
            ],
            [
                [],
                'Option "fromFile" must be a string, got "<null>".',
            ],
        ];
    }

    /**
     * @param string $expected
     * @param string $from
     * @param string $to
     * @param int    $threshold
     *
     * @dataProvider provideCommonLineThresholdCases
     */
    public function testCommonLineThreshold(string $expected, string $from, string $to, int $threshold): void
    {
        $diff = $this->getDiffer([
            'fromFile'            => 'input.txt',
            'toFile'              => 'output.txt',
            'commonLineThreshold' => $threshold,
            'contextLines'        => 0,
        ])->diff($from, $to);

        $this->assertValidDiffFormat($diff);
        $this->assertSame($expected, $diff);
    }

    public function provideCommonLineThresholdCases(): array
    {
        return [
            [
'--- input.txt
+++ output.txt
@@ -2,3 +2,3 @@
-X
+B
 C12
-Y
+D
@@ -7 +7 @@
-X
+Z
',
                "A\nX\nC12\nY\nA\nA\nX\n",
                "A\nB\nC12\nD\nA\nA\nZ\n",
                2,
            ],
            [
'--- input.txt
+++ output.txt
@@ -2 +2 @@
-X
+B
@@ -4 +4 @@
-Y
+D
',
                "A\nX\nV\nY\n",
                "A\nB\nV\nD\n",
                1,
            ],
        ];
    }

    /**
     * @param string $expected
     * @param string $from
     * @param string $to
     * @param int    $contextLines
     * @param int    $commonLineThreshold
     *
     * @dataProvider provideContextLineConfigurationCases
     */
    public function testContextLineConfiguration(string $expected, string $from, string $to, int $contextLines, int $commonLineThreshold = 6): void
    {
        $diff = $this->getDiffer([
            'fromFile'            => 'input.txt',
            'toFile'              => 'output.txt',
            'contextLines'        => $contextLines,
            'commonLineThreshold' => $commonLineThreshold,
        ])->diff($from, $to);

        $this->assertValidDiffFormat($diff);
        $this->assertSame($expected, $diff);
    }

    public function provideContextLineConfigurationCases(): array
    {
        $from = "A\nB\nC\nD\nE\nF\nX\nG\nH\nI\nJ\nK\nL\nM\n";
        $to   = "A\nB\nC\nD\nE\nF\nY\nG\nH\nI\nJ\nK\nL\nM\n";

        return [
            'EOF 0' => [
                "--- input.txt\n+++ output.txt\n@@ -3 +3 @@
-X
\\ No newline at end of file
+Y
\\ No newline at end of file
",
                "A\nB\nX",
                "A\nB\nY",
                0,
            ],
            'EOF 1' => [
                "--- input.txt\n+++ output.txt\n@@ -2,2 +2,2 @@
 B
-X
