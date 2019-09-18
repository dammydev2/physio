<?php
/*
 * This file is part of the php-code-coverage package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\CodeCoverage\Driver;

use SebastianBergmann\CodeCoverage\RuntimeException;

/**
 * Driver for PHPDBG's code coverage functionality.
 *
 * @codeCoverageIgnore
 */
final class PHPDBG implements Driver
{
    /**
     * @throws RuntimeException
     */
    public function __construct()
    {
        if (\PHP_SAPI !== 'phpdbg') {
            throw new RuntimeException(
                'This driver requires the PHPDBG SAPI'
         