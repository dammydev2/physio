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

final class Crap4j
{
    /**
     * @var int
     */
    private $threshold;

    public function __construct(int $threshold = 30)
    {
        $this->threshold = $threshold;
    }

    /**
     * @throws \RuntimeException
     */
    public function process(CodeCoverage $coverage, ?string $target = null, ?string $name = null): string
    {
        $document               = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = true;

        $root = $document->createElement('crap_result');
        $document->appendChild($root);

        $project = $document->createElement('project', \is_string($name) ? $name : '');
        $root->appendChild($project);
        $root->appendChild($document->createElement('timestamp', \date('Y-m-d H:i:s', (int) $_SERVER['REQUEST_TIME'])));

        $stats       = $document->createElement('stats');
        $methodsNode = $document->createElement('methods');

        $report = $coverage->getReport();
        unset($coverage);

        $fullMethodCount     = 0;
        $fullCrapMethodCount = 0;
        $fullCrapLoad        = 0;
        $fullCrap            = 0;

   