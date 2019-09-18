   $stats->appendChild($document->createElement('totalCrap', $fullCrap));

        $crapMethodPercent = 0;

        if ($fullMethodCount > 0) {
            $crapMethodPercent = $this->roundValue((100 * $fullCrapMethodCount) / $fullMethodCount);
        }

        $stats->appendChild($document->createElement('crapMethodPercent', $crapMethodPercent));

        $root->appendChild($stats);
        $root->appendChild($methodsNode);

        $buffer = $document->saveXML();

        if ($target !== null) {
            if (!$this->createDirectory(\dirname($target))) {
                throw new \RuntimeException(\sprintf('Directory "%s" was not created', \dirname($target)));
            }

            if (@\file_put_contents($target, $buffer) === false) {
                throw new RuntimeException(
                    \sprintf(
                        'Could not write to "%s',
                        $target
                    )
                );
            }
        }

        return $buffer;
    }

    /**
     * @param float $crapValue
     * @param int   $cyclomaticComplexity
     * @param float $coveragePercent
     */
    private function getCrapLoad($crapValue, $cyclomaticComplexity, $coveragePercent): float
    {
        $crapLoad = 0;

        if ($crapValue >= $this->threshold) 