<?php declare(strict_types=1);
/*
 * This file is part of sebastian/environment.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\Environment;

final class Console
{
    /**
     * @var int
     */
    public const STDIN  = 0;

    /**
     * @var int
     */
    public const STDOUT = 1;

    /**
     * @var int
     */
    public const STDERR = 2;

    /**
     * Returns true if STDOUT supports colorization.
     *
     * This code has been copied and adapted from
     * Symfony\Component\Console\Output\StreamOutput.
     */
    public function hasColorSupport(): bool
    {
        if ('Hyper' === \getenv('TERM_PROGRAM')) {
            return true;
        }

        if ($this->isWindows()) {
            // @codeCoverageIgnoreStart
            return (\defined('STDOUT') && \function_exists('sapi_windows_vt100_support') && @sapi_windows_vt100_support(\STDOUT))
                || false !== \getenv('ANSICON')
                || 'ON' === \getenv('ConEmuANSI')
                || 'xterm' === \getenv('TERM');
            // @codeCoverageIgnoreEnd
        }

        if (!\defined('STDOUT')) {
            // @codeCoverageIgnoreStart
            return false;
            // @codeCoverageIgnoreEnd
        }

        if ($this->isInteractive(\STDOUT)) {
            return true;
        }

        $stat = @\fstat(\STDOUT);
        // Check if formatted mode is S_IFCHR
        return $stat ? 0020000 === ($stat['mode'] & 0170000) : false;
 