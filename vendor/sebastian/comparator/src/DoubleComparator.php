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

use SebastianBergmann\Diff\Differ;

/**
 * Builds a diff string representation in unified diff format in chunks.
 */
final class UnifiedDiffOutputBuilder extends AbstractChunkOutputBuilder
{
    /**
     * @var bool
     */
    private $collapseRanges = true;

    /**
     * @var int >= 0
     */
    private $commonLineThreshold = 6;

    /**
     * @var int >= 0
     */
    private $contextLines = 3;

    /**
     * @var string
     */
    private $header;

    /**
     * @var bool
     */
    private $addLineNumbers;

    public function __construct(string $header = "--- Original\n+++ New\n", bool $addLineNumbers = false)
    {
        $this->header         = $header;
        $this->addLineNumbers = $addLineNumbers;
    }

    public function getDiff(array $diff): string
    {
        $buffer = \fopen('php://memory', 'r+b');

        if ('' !== $this->header) {
            \fwrite($buffer, $this->header);

            if ("\n" !== \substr($this->header, -1, 1)) {
                \fwrite($buffer, "\n");
            }
        }

        if (0 !== \count($diff)) {
            $this->writeDiffHunks($buffer, $diff);
        }

        $diff = \stream_get_contents($buffer, -1, 0);

        \fclose($buffer);

        // If the last char is not a linebreak: add it.
        // This might happen when both the `from` and `to` do not hav