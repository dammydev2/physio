<?php declare(strict_types=1);
/*
 * This file is part of sebastian/diff.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\Diff\Utils;

use PHPUnit\Framework\TestCase;

/**
 * @covers SebastianBergmann\Diff\Utils\UnifiedDiffAssertTrait
 */
final class UnifiedDiffAssertTraitTest extends TestCase
{
    use UnifiedDiffAssertTrait;

    /**
     * @param string $diff
     *
     * @dataProvider provideValidCases
     */
    public function testValidCases(string $diff): void
    {
        $this->assertValidUnifiedDiffFormat($diff);
    }

    public function provideValidCases(): array
    {
        return [
            [
'--- Original
+++ New
@@ -8 +8 @@
-Z
+U
',
            ],
            [
'--- Original
+++ New
@@ -8 +8 @@
-Z
+U
@@ -15 +15 @@
-X
+V
',
            ],
            'empty diff. is valid' => [
                '',
            ],
        ];
    }

    public function testNoLinebreakEnd(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessageRegExp(\sprintf('#^%s$#', \preg_quote('Expected diff to end with a line break, got "C".', '#')));

        $this->assertValidUnifiedDiffFormat("A\nB\nC");
    }

    public function testInvalidStartWithoutHeader(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessageRegExp(\sprintf('#^%s$#', \preg_quote("Expected line to start with '@', '-' or '+', got \"A\n\". Line 1.", '#')));

        $th