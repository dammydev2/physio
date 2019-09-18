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

final class MemoryEfficientLongestCommonSubsequenceCalculator implements LongestCommonSubsequenceCalculator
{
    /**
     * {@inheritdoc}
     */
    public function calculate(array $from, array $to): array
    {
        $cFrom = \count($from);
        $cTo   = \count($to);

        if ($cFrom === 0) {
            return [];
        }

        if ($cFrom === 1) {
            if (\in_array($from[0], $to, true)) {
                return [$from[0]];
            }

            return [];
        }

        $i         = (int) ($cFrom / 2);
        $fromStart = \array_slice($from, 0, $i);
        $fromEnd   = \array_slice($from, $i);
        $llB       = $this->length($fromStart, $to);
        $llE       = $this->length(\array_reverse($fromEnd), \array_reverse($to));
        $jMax      = 0;
        $max       = 0;

        for ($j = 0; $j <