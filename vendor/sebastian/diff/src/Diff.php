ectedValueException::class);
        $this->expectExceptionMessageRegExp(\sprintf('#^%s$#', \preg_quote("Hunk header line does not match expected pattern, got \"@@ INVALID -1,1 +1,1 @@\n\". Line 3.", '#')));

        $this->assertValidUnifiedDiffFormat(
'--- Original
+++ New
@@ INVALID -1,1 +1,1 @@
-Z
+U
'
        );
    }

    public function testHunkOverlapFrom(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessageRegExp(\sprintf('#^%s$#', \preg_quote('Unexpected new hunk; "from" (\'-\') start overlaps previous hunk. Line 6.', '#')));

        $this->assertValidUnifiedDiffFormat(
'--- Original
+++ New
@@ -8,1 +8,1 @@
-Z
+U
@@ -7,1 +9,1 @@
-Z
+U
'
        );
    }

    public function testHunkOverlapTo(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessageRegExp(\sprintf('#^%s$#', \preg_quote('Unexpected new hunk; "to" (\'+\') start overlaps previous hunk. Line 6.', '#')));

        $this->assertValidUnifiedDiffFormat(
'--- Original
+++ New
@@ -8,1 +8,1 @@
-Z
+U
@@ -17,1 +7,1 @@
-Z
+U
'
        );
    }

    public function testExpectHunk1()