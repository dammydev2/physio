f(
                    '- %s is expected to be executed (@uses) but was not executed' . "\n",
                    $codeUnit
                );
            }
        }

        if (!empty($message)) {
            throw new CoveredCodeNotExecutedException($message);
        }
    }

    private function getAllowedLines(array $linesToBeCovered, array $linesToBeUsed): array
    {
        $allowedLines = [];

        foreach (\array_keys($linesToBeCovered) as $file) {
            if (!isset($allowedLines[$file])) {
                $allowedLines[$file] = [];
            }

            $allowedLines[$file] = \array_merge(
                $allowedLines[$file],
                $linesToBeCovered[$file]
            );
        }

        foreach (\array_keys($linesToBeUsed) as $file) {
            if (!isset($allowedLines[$file])) {
                $allowedLines[$file] = [];
            }

            $allowedLines[$file] = \array_merge(
                $allowedLines[$file],
                $linesToBeUsed[$file]
            );
        }

        foreach (\array_keys($allowedLines) as $file) {
            $allowedLines[$file] = \array_flip(
                \array_unique($allowedLines[$file])
            );
        }

        return $allowedLines;
    }

    /**
     * @throws RuntimeException
     */
    private function selectDriver(Filter $filter): Driver
    {
        $runtime = new Runtime;

        if (!$runtime->canCollectCodeCoverage()) {
            throw new RuntimeException('No code coverage driver available');
        }

        if ($runtime->isPHPDBG()) {
            return new PHPDBG;
        }

        if ($runtime->hasXdebug()) {
            return new Xdebug($filter);
        }

        throw new RuntimeException('No code coverage driver available');
    }

    private function processUnintentionallyCoveredUnits(array $unintentionallyCoveredUnits): array
    {
        $unintentionallyCoveredUnits = \array_unique($unintentionallyCoveredUnits);
        \sort($unintentionallyCoveredUnits);

        foreach (\array_keys($unintentionallyCoveredUnits) as $k => $v) {
            $unit = \explode('::', $unintentionallyCoveredUnits[$k]);

            if (\count($unit) !== 2) {
                continue;
            }

            $class = new \ReflectionClass($unit[0]);

            foreach ($this->unintentionallyCoveredSubclassesWhitelist as $whitelisted) {
                if ($class->isSubclassOf($whitelisted)) {
                    unset($unintentionallyCoveredUnits[$k]);

                    break;
                }
            }
        }

        return \array_values($unintentionallyCoveredUnits);
    }

    /**
     * @throws CoveredCodeNotExecutedException
     * @throws InvalidArgumentException
     * @throws MissingCoversAnnotationException
     * @throws RuntimeException
     * @throws UnintentionallyCoveredCodeException
     * @throws \ReflectionException
     */
    private function initializeData(): void
    {
        $this->isInitialized = true;

        if ($this->processUncoveredFilesFromWhitelist) {
            $this->shouldCheckForDeadAndUnused = false;

            $this->driver->start();

            foreach ($this->filter->getWhitelist() as $file) {
                if ($this->filter->isFile($file)) {
                    include_once $file;
                }
            }

            $data     = [];
            $coverage = $this->driver->stop();

            foreach ($coverage as $file => $fileCoverage) {
                if ($this->filter->isFiltered($file)) {
                    continue;
                }

                foreach (\array_keys($fileCoverage) as $key) {
                    if ($fileCoverage[$key] === Driver::LINE_EXECUTED) {
                        $fileCo