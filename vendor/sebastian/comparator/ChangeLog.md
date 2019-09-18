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

use SebastianBergmann\Diff\Output\DiffOutputBuilderInterface;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

/**
 * Diff implementation.
 */
final class Differ
{
    public const OLD                     = 0;
    public const ADDED                   = 1;
    public const REMOVED                 = 2;
    public const DIFF_LINE_END_WARNING   = 3;
    public const NO_LINE_END_EOF_WARNING = 4;

    /**
     * @var DiffOutputBuilderInterface
     */
    private $outputBuilder;

    /**
     * @param DiffOutputBuilderInterface $outputBuilder
     *
     * @throws InvalidArgumentException
     */
    public function __construct($outputBuilder = null)
    {
        if ($outputBuilder instanceof DiffOutputBuilderInterface) {
            $this->outputBuilder = $outputBuilder;
        } elseif (null === $outputBuilder) {
            $this->outputBuilder = new UnifiedDiffOutputBuilder;
        } elseif (\is_string($outputBuilder)) {
            // PHPUnit 6.1.4, 6.2.0, 6.2.1, 6.2.2, and 6.2.3 support
            // @see https://github.com/sebastianbergmann/phpunit/issues/2734#issuecomment-314514056
            // @deprecated
            $this->outputBuilder = new UnifiedDiffOutputBuilder($outputBuilder);
        } else {
            throw new InvalidArgumentException(
                \sprintf(
                    'Expected builder to be an instance of DiffOutputBuilderInterface, <null> or a string, got %s.',
                    \is_object($outputBuilder) ? 'instance of "' . \get_class($outputBuilder) . '"' : \gettype($outputBuilder) . ' "' . $outputBuilder . '"'
   