gmann\CodeCoverage\CoveredCodeNotExecutedException
     * @throws \ReflectionException
     * @throws MissingCoversAnnotationException
     * @throws UnintentionallyCoveredCodeException
     */
    private function applyCoversAnnotationFilter(array &$data, $linesToBeCovered, array $linesToBeUsed, bool $ignoreForceCoversAnnotation): void
    {
        if ($linesToBeCovered === false ||
            ($this->forceCoversAnnotation && empty($linesToBeCovered) && !$ignoreForceCoversAnnotation)) {
            if ($this->checkForMissingCoversAnnotation) {
                throw new MissingCoversAnnotationException;
            }

            $data = [];

            return;
        }

        if (empty($linesToBeCovered)) {
            return;
        }

        if ($this->checkForUnintentionallyCoveredCode &&
            (!$this->currentId instanceof TestCase ||
            (!$this->currentId->isMedium() && !$this->currentId->isLarge()))) {
            $this->performUnintentionallyCoveredCodeCheck($