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

use SebastianBergmann\Diff\ConfigurationException;
use SebastianBergmann\Diff\Differ;

/**
 * Strict Unified diff output builder.
 *
 * Generates (strict) Unified diff's (unidiffs) with hunks.
 */
final class StrictUnifiedDiffOutputBuilder implements DiffOutputBuilderInterface
{
    private static $default = [
        'collapseRanges'      => true, // ranges of length one are rendered with the trailing `,1`
        'commonLineThreshold' => 6,    // number of same lines before ending a new hunk and creating a new one (if needed)
        'contextLines'        => 3,    // like `diff:  -u, -U NUM, --unified[=NUM]`, for patch/git apply compatibility best to keep at least @ 3
        'fromFile'            => null,
        'fromFileDate'        => null,
        'toFile'              => null,
        'toFileDate'          => null,
    ];
    /**
     * @var bool
     */
    private $changed;

    /**
     * @var bool
     */
    private $collapseRanges;

    /**
     * @var int >= 0
     */
    private $commonLineThreshold;

    /**
     * @var string
     */
    private $header;

    /**
     * @var int >= 0
     */
    private $contextLines;

    public function __construct(array $options = [])
    {
        $options = \array_merge(self::$default, $options);

        if (!\is_bool($options['collapseRanges'])) {
            throw new ConfigurationException('collapseRanges', 'a bool', $options['collapseRanges']);
        }

        if (!\is_int($options['contextLines']) || $options['contextLines'] < 0) {
            throw new ConfigurationException('contextLines', 'an int >= 0', $options['contextLines']);
        }

        if (!\is_int($options['commonLineThreshold']) || $options['commonLineThreshold'] <= 0) {
            throw new ConfigurationException('commonLineThreshold', 'an int > 0', $options['commonLineThreshold']);
        }

        foreach (['fromFile', 'toFile'] as $option) {
            if (!\is_string($options[$option])) {
                throw new ConfigurationException($option, 'a string', $options[$option]);
            }
        }

        foreach (['fromFileDate', 'toFileDate'] as $option) {
            if (null !== $options[$option] && !\is_string($options[$option])) {
                throw new ConfigurationException($option, 'a string or <null>', $options[$option]);
            }
        }

        $this->header = \sprintf(
            "--- %s%s\n+++ %s%s\n",
            $options['fromFile'],
            null === $options['fromFileDate'] ? '' : "\t" . $options['fromFileDate'],
            $options['toFile'],
            null === $options['toFileDate'] ? '' : "\t" . $options['toFileDate']
        );

        $this->collapseRanges      = $options['collapseR