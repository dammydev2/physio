<?php declare(strict_types=1);
/*
 * This file is part of sebastian/diff.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\Diff;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

/**
 * @covers SebastianBergmann\Diff\Differ
 * @covers SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder
 *
 * @uses SebastianBergmann\Diff\MemoryEfficientLongestCommonSubsequenceCalculator
 * @uses SebastianBergmann\Diff\TimeEfficientLongestCommonSubsequenceCalculator
 * @uses SebastianBergmann\Diff\Output\AbstractChunkOutputBuilder
 */
final class DifferTest extends TestCase
{
    /**
     * @var Differ
     */
    private $differ;

    protected function setUp(): void
    {
        $this->differ = new Differ;
    }

    /**
     * @param array        $expected
     * @param array|string $from
     * @param array|string $to
     *
     * @dataProvider arrayProvider
     */
    public function testArrayRepresentationOfDiffCanBeRenderedUsingTimeEfficientLcsImplementation(array $expected, $from, $to): void
    {
        $this->assertSame($expected, $this->differ->diffToArray($from, $to, new TimeEfficientLongestCommonSubsequenceCalculator));
    }

    /**
     * @param string $expected
     * @param string $from
     * @param string $to
     *
     * @dataProvider textProvider
     */
    public function testTextRepresentationOfDiffCanBeRenderedUsingTimeEfficientLcsImplementation(string $expected, string $from, string $to): void
    {
        $this->assertSame($expected, $this->differ->diff($from, $to, new TimeEfficientLongestCommonSubsequenceCalculator));
    }

    /**
     * @param array        $expected
     * @param array|string $from
     * @param array|string $to
     *
     * @dataProvider arrayProvider
     */
    public function testArrayRepresentationOfDiffCanBeRenderedUsingMemoryEfficientLcsImplementation(array $expected, $from, $to): void
    {
        $this->assertSame($expected, $this->differ->diffToArray($from, $to, new MemoryEfficientLongestCommonSubsequenceCalculator));
    }

    /**
     * @param string $expected
     * @param string $from
     * @param string $to
     *
     * @dataProvider textProvider
     */
    public function testTextRepresentationOfDiffCanBeRenderedUsingMemoryEfficientLcsImplementation(string $expected, string $from, string $to): void
    {
        $this->assertSame($expected, $this->differ->diff($from, $to, new MemoryEfficientLongestCommonSubsequenceCalculator));
    }

    public function testTypesOtherThanArrayAndStringCanBePassed(): void
    {
        $this->assertSame(
            "--- Original\n+++ New\n@@ @@\n-1\n+2\n",
            $this->differ->diff(1, 2)
        );
    }

    public function testArrayDiffs(): void
    {
        $this->assertSame(
            '--- Original
+++ New
@@ @@
-one
+two
',
            $this->differ->diff(['one'], ['two'])
        );
    }

    public function arrayProvider(): array
    {
        return [
            [
                [
                    ['a', Differ::REMOVED],
                    ['b', Differ::ADDED],
                ],
                'a',
                'b',
            ],
            [
                [
                    ['ba', Differ::REMOVED],
                    ['bc', Differ::ADDED],
                ],
                'ba',
                'bc',
            ],
            [
                [
                    ['ab', Differ::REMOVED],
             