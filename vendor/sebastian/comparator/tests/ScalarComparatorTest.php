 E\n F\n-X\n+Y\n G\n H\n I\n",
                $from,
                $to,
                3,
            ],
            'J' => [
                "--- input.txt\n+++ output.txt\n@@ -3,9 +3,9 @@\n C\n D\n E\n F\n-X\n+Y\n G\n H\n I\n J\n",
                $from,
                $to,
                4,
            ],
            'K' => [
                "--- input.txt\n+++ output.txt\n@@ -2,11 +2,11 @@\n B\n C\n D\n E\n F\n-X\n+Y\n G\n H\n I\n J\n K\n",
                $from,
                $to,
                5,
            ],
            'L' => [
                "--- input.txt\n+++ output.txt\n@@ -1,13 +1,13 @@\n A\n B\n C\n D\n E\n F\n-X\n+Y\n G\n H\n I\n J\n K\n L\n",
                $from,
                $to,
                6,
            ],
            'M' => [
                "--- input.txt\n+++ output.txt\n@@ -1,14 +1,14 @@\n A\n B\n C\n D\n E\n F\n-X\n+Y\n G\n H\n I\n J\n K\n L\n M\n",
                $from,
                $to,
                7,
            ],
            'M no linebreak EOF .1' => [
                "--- input.txt\n+++ output.txt\n@@ -1,14 +1,14 @@\n A\n B\n C\n D\n E\n F\n-X\n+Y\n G\n H\n I\n J\n K\n L\n-M\n+M\n\\ No newline at end of file\n",
                $from,
                \substr($to, 0, -1),
                7,
            ],
            'M no linebreak EOF .2' => [
                "--- input.txt\n+++ output.txt\n@@ -1,14 +1,14 @@\n A\n B\n C\n D\n E\n F\n-X\n+Y\n G\n H\n I\n J\n K\n L\n-M\n\\ No newline at end of file\n+M\n",
                \substr($from, 0, -1),
                $to,
                7,
            ],
            'M no linebreak EOF .3' => [
                "--- input.txt\n+++ output.txt\n@@ -1,14 +1,14 @@\n A\n B\n C\n D\n E\n F\n-X\n+Y\n G\n H\n I\n J\n K\n L\n M\n",
                \substr($from, 0, -1),
                \substr($to, 0, -1),
                7,
            ],
            'M no linebreak EOF .4' => [
                "--- input.txt\n+++ output.txt\n@@ -1,14 +1,14 @@\n A\n B\n C\n D\n E\n F\n-X\n+Y\n G\n H\n I\n J\n K\n L\n M\n\\ No newline at end of file\n",
                \substr($from, 0, -1),
                \substr($to, 0, -1),
                10000,
                10000,
            ],
            'M+1' => [
                "--- input.txt\n+++ output.txt\n@@ -1,14 +1,14 @@\n A\n B\n C\n D\n E\n F\n-X\n+Y\n G\n H\n I\n J\n K\n L\n M\n",
                $from,
                $to,
                8,
            ],
            'M+100' => [
                "--- input.txt\n+++ output.txt\n@@ -1,14 +1,14 @@\n A\n B\n C\n D\n E\n F\n-X\n+Y\n G\n H\n I\n J\n K\n L\n M\n",
                $from,
                $to,
                107,
            ],
            '0 II' => [
                "--- input.txt\n+++ output.txt\n@@ -12 +12 @@\n-X\n+Y\n",
                "A\nB\nC\nD\nE\nF\nG\nH\nI\nJ\nK\nX\nM\n",
                "A\nB\nC\nD\nE\nF\nG\nH\nI\nJ\nK\nY\nM\n",
                0,
                999,
            ],
            '0\' II' => [
                "--- input.txt\n+++ output.txt\n@@ -12 +12 @@\n-X\n+Y\n",
                "A\nB\nC\nD\nE\nF\nG\nH\nI\nJ\nK\nX\nM\nA\nA\nA\nA\nA\n",
                "A\nB\nC\nD\nE\nF\nG\nH\nI\nJ\nK\nY\nM\nA\nA\nA\nA\nA\n",
                0,
                999,
            ],
            '0\'\' II' => [
                "--- input.txt\n+++ output.txt\n@@ -12,2 +12,2 @@\n-X\n-M\n\\ No newline at end of file\n+Y\n+M\n",
                "A\nB\nC\nD\nE\nF\nG\nH\nI\nJ\nK\nX\nM",
                "A\nB\nC\nD\nE\nF\nG\nH\nI\nJ\nK\nY\nM\n",
                0,
            ],
            '0\'\'\' II' => [
                "--- input.txt\n+++ output.txt\n@@ -12,2 +12,2 @@\n-X\n-X1\n+Y\n+Y2\n",
                "A\nB\nC\nD\nE\nF\nG\nH\nI\nJ\nK\nX\nX1\nM\nA\nA\nA\nA\nA\n",
                "A\nB\nC\nD\nE\nF\nG\nH\nI\nJ\nK\nY\nY2\nM\nA\nA\nA\nA\nA\n",
                0,
                999,
            ],
            '1 II' => [
                "--- input.txt\n+++ output.txt\n@@ -11,3 +11,3 @@\n K\n-X\n+Y\n M\n",
                "A\nB\nC\nD\nE\nF\nG\nH\nI\nJ\nK\nX\nM\n",
                "A\nB\nC\nD\nE\nF\nG\nH\nI\nJ\nK\nY\nM\n",
                1,
            ],
            '5 II' => [
                "--- input.txt\n+++ output.txt\n@@ -7,7 +7,7 @@\n G\n H\n I\n J\n K\n-X\n+Y\n M\n",
                "A\nB\nC\nD\nE\nF\nG\nH\nI\nJ\nK\nX\nM\n",
                "A\nB\nC\nD\nE\nF\nG\nH\nI\nJ\nK\nY\nM\n",
                5,
            ],
            [
                '--- input.txt
+++ output.txt
@@ -1,28 +1,28 @@
 A
-X
+B
 V
-Y
+D
 1
 A
 2
 A
 3
 A
 4
 A
 8
 A
 9
 A
 5
 A
 A
 A
 A
 A
 A
 A
 A
 A
 A
 A
',
                "A\nX\nV\nY\n1\nA\n2\nA\n3\nA\n4\nA\n8\nA\n9\nA\n5\nA\nA\nA\nA\nA\nA\nA\nA\nA\nA\nA\n",
                "A\nB\nV\nD\n1\nA\n2\nA\n3\nA\n4\nA\n8\nA\n9\nA\n5\nA\nA\nA\nA\nA\nA\nA\nA\nA\nA\nA\n",
                9999,
                99999,
            ],
        ];
    }

    /**
     * Returns a new instan