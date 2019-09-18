<?php declare(strict_types=1);
/*
 * This file is part of sebastian/diff.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\Diff\Output;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Diff\ConfigurationException;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Utils\UnifiedDiffAssertTrait;

/**
 * @covers SebastianBergmann\Diff\Output\StrictUnifiedDiffOutputBuilder
 *
 * @uses SebastianBergmann\Diff\Differ
 */
final class StrictUnifiedDiffOutputBuilderTest extends TestCase
{
    use UnifiedDiffAssertTrait;

    /**
     * @param string $expected
     * @param string $from
     * @param string $to
     * @param array  $options
     *
     * @dataProvider provideOutputBuildingCases
     */
    public function testOutputBuilding(string $expected, string $from, string $to, array $options): void
    {
        $diff = $this->getDiffer($options)->diff($from, $to);

        $this->assertValidDiffFormat($diff);
        $this->assertSame($expected, $diff);
    }

    /**
     * @param string $expected
     * @param string $from
     * @param string $to
     * @param array  $options
     *
     * @dataProvider provideSample
     */
    public function testSample(string $expected, string $from, string $to, array $options): void
    {
        $diff = $this->getDiffer($options)->diff($from, $to);

        $this->assertValidDiffFormat($diff);
        $this->assertSame($expected, $diff);
    }

    /**
     * {@inheritdoc}
     */
    public function assertValidDiffFormat(string $diff): void
    {
        $this->assertValidUnifiedDiffFormat($diff);
    }

    /**
     * {@inheritdoc}
     */
    public function provideOutputBuildingCases(): array
    {
        return StrictUnifiedDiffOutputBuilderDataProvider::provideOutputBuildingCases();
    }

    /**
     * {@inheritdoc}
     */
    public function provideSample(): array
    {
        return StrictUnifiedDiffOutputBuilderDataProvider::provideSample();
    }

    /**
     * @param string $expected
     * @param string $from
     * @param string $to
     *
     * @dataProvider provideBasicDiffGeneration
     */
    public function testBasicDiffGeneration(string $expected, string $from, string $to): void
    {
        $diff = $this->getDiffer([
            'fromFile' => 'input.txt',
            'toFile'   => 'output.txt',
        ])->diff($from, $to);

        $this->assertValidDiffFormat($diff);
        $this->assertSame($expected, $diff);
    }

    public function provideBasicDiffGeneration(): array
    {
        return StrictUnifiedDiffOutputBuilderDataProvider::provideBasicDiffGeneration();
    }

    /**
     * @param string $expected
     * @param string $from
     * @param string $to
     * @param array  $config
     *
     * @dataProvider provideConfiguredDiffGeneration
     */
    public function testConfiguredDiffGeneration(string $expected, string $from, string $to, array $config = []): void
    {
        $diff = $this->getDiffer(\array_merge([
            'fromFile' => 'input.txt',
            'toFile'   => 'output.txt',
        ], $config))->diff($from, $to);

        $this->assertValidDiffFormat($diff);
        $this->assertSame($expected, $diff);
    }

    public function provideConfiguredDiffGeneration(): array
    {
        return [
            [
                '--- input.txt
+++ output.txt
@@ -1 +1 @@
-a
\ No newline at end of file
+b
\ No newline at end of file
',
                'a',
                'b',
            ],
            [
                '',
                "1\n2",
                "1\n2",
            ],
            [
                '',
                "1\n",
                "1\n",
            ],
            [
'--- input.txt
+++ output.txt
@@ -4 +4 @@
-X
+4
',
                "1\n2\n3\nX\n5\n6\n7\n8\n9\n0\n",
                "1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n",
                [
                    'contextLines' => 0,
                ],
            ],
            [
'--- input.txt
+++ output.txt
@@ -3,3 +3,3 @@
 3
-X
+4
 5
',
                "1\n2\n3\nX\n5\n6\n7\n8\n9\n0\n",
                "1\n2\n3\n4\n5\n6\n7\n8\n9\n0\n",