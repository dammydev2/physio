<?php
/*
 * This file is part of the php-code-coverage package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\CodeCoverage\Report;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Node\File;
use SebastianBergmann\CodeCoverage\RuntimeException;

/**
 * Generates a Clover XML logfile from a code coverage object.
 */
final class Clover
{
    /**
     * @throws \RuntimeException
     */
    public function process(CodeCoverage $coverage, ?string $target = null, ?string $name = null): string
    {
        $xmlDocument               = new \DOMDocument('1.0', 'UTF-8');
        $xmlDocument->formatOutput = true;

        $xmlCoverage = $xmlDocument->createElement('coverage');
        $xmlCoverage->setAttribute('generated'