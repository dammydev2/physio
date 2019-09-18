<?php
/*
 * This file is part of the php-code-coverage package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\CodeCoverage\Node;

/**
 * Represents a file in the code coverage information tree.
 */
final class File extends AbstractNode
{
    /**
     * @var array
     */
    private $coverageData;

    /**
     * @var array
     */
    private $testData;

    /**
     * @var int
     */
    private $numExecutableLines = 0;

    /**
     * @var int
     */
    private $numExecutedLines = 0;

    /**
     * @var array
     */
    private $classes = [];

    /**
     * @var array
     */
    private $traits = [];

    /**
     * @var array
     */
    private $functions = [];

    /**
     * @var array
  