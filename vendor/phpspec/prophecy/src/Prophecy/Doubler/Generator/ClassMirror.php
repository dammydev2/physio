 $data;
        $this->report = null;
    }

    /**
     * Returns the test data.
     */
    public function getTests(): array
    {
        return $this->tests;
    }

    /**
     * Sets the test data.
     */
    public function setTests(array $tests): void
    {
        $this->tests = $tests;
    }

    /**
     * Start collection of code coverage information.
     *
     * @param PhptTestCase|string|TestCase $id
     *
     * @throws RuntimeException
     */
    public function start($id, bool $clear = false): void
    {
        if ($clear) {
            $this->clear();
        }

        if ($this->isInitialized === false) {
            $this->initializeData();
        }

        $this->currentId = $id;

        $this->driver->start($this->shouldCheckForDeadAndUnused);
    }

    /**
     * Stop collection of code coverage information.
     *
     * @param array|false $linesToBeCovered
     *
     * @throws MissingCoversAnnotationException
     * @throws CoveredCodeNotExecutedException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws \ReflectionException
     */
    public function stop(bool $append = true, $linesToBeCovered = [], array $linesToBeUsed = [], bool $ignoreForceCoversAnnotation = false): array
    {
        if (!\is_array($linesToBeCovered) && $linesToBeCovered !== false) {
            throw InvalidArgumentException::create(
                2,
                'array or false'
            );
        }

        $data = $this->driver->stop();
        $this->append($data, null, $append, $linesToBeCovered, $linesToBeUsed, $ignoreForceCoversAnnotation);

        $this->currentId = null;

        return $data;
    }

    /**
     * Appends code coverage data.
     *
     * @param PhptTestCase|string|TestCase $id
     * @param array|false                  $linesToBeCovered
     *
     * @throws \SebastianBergmann\CodeCoverage\UnintentionallyCoveredCodeException
     * @throws \SebastianBergmann\CodeCoverage\MissingCoversAnnotationException
     * @throws \SebastianBergmann\CodeCoverage\CoveredCodeNotExecutedException
     * @throws \ReflectionException
     * @throws \SebastianBergmann\CodeCoverage\InvalidArgumentException
     * @throws RuntimeException
     */
    public function append(array $data, $id = null, bool $append = true, $linesToBeCovered = [], array $linesToBeUsed = [], bool $ignoreForceCoversAnnotation = false): void
    {
        if ($id === null) {
            $id = $this->currentId;
        }

        if ($id === null) {
            throw new RuntimeException;
        }

        $this->applyWhitelistFilter($data);
        $this->applyIgnoredLinesFilter($data);
        $this->initializeFilesThatAreSeenTheFirstTime($data);

        if (!$append) {
            return;
        }

        if ($id !== 'UNCOVERED_FILES_FROM_WHITELIST') {
            $this->applyCoversAnnotationFilter(
                $data,
                $linesToBeCovered,
                $linesToBeUsed,
                $ignoreForceCoversAnnotation
            );
        }

        if (empty($data)) {
            return;
        }

        $size   = 'unknown';
        $status = -1;

        if ($id instanceof TestCase) {
            $_size = $id->getSize();

            if ($_size === Test::SMALL) {
                $size = 'small';
            } elseif ($_size === Test::MEDIUM) {
                $size = 'medium';
            } elseif ($_size === Test::LARGE) {
                $size = 'large';
            }

            $status = $id->getStatus();
            $id     = \get_class($id) . '::' . $id->getName();
        } elseif ($id instanceof PhptTestCase) {
            $size = 'large';
            $id   = $id->getName();
        }

        $this->tests[$id] = ['size' => $size, 'status' => $status];

        foreach ($data as $file => $lines) {
            if (!$this->filter->isFile($file)) {
                continue;
            }

            foreach ($lines as $k => $v) {
                if ($v === Driver::LINE_EXECUTED) {
                    if (empty($this->data[$file][$k]) || !\in_array($id, $this->data[$file][$k])) {
                        $this->data[$file][$k][] = $id;
                    }
                }
            }
        }

        $this->report = null;
    }

    /**
     * Merges the data from another instance.
     *
     * @param CodeCoverage $that
     */
    public function merge(self $that): void
    {
        $this->filter->setWhitelistedFiles(
            \array_merge($this->filter->getWhitelistedFiles(), $that->filter()->getWhitelistedFiles())
        );

        foreach ($that->data as $file => $lines) {
            if (!isset($this->data[$file])) {
                if (!$this->filter->isFiltered($file)) {
                    $this->data[$file] = $lines;
                }

                continue;
            }

            // we should compare the lines if any of two contains data
            $compareLineNumbers = \array_unique(
                \array_merge(
                    \array_keys($this->data[$file]),
                    \array_keys($that->data[$file])
                )
            );

            foreach ($compareLineNumbers as $line) {
                $thatPriority = $this->getLinePriority($that->data[$file], $line);
                $thisPriority = $this->getLinePriority($this->data[$file], $line);

                if ($thatPriority > $thisPriority) {
                    $this->data[$file][$line] = $that->data[$file][$line];
                } elseif ($thatPriority === $thisPriority && \is_array($this->data[$file][$line])) {
                    $this->data[$file][$line] = \array_unique(
                        \array_merge($this->data[$file][$line], $that->data[$file][$line])
                    );
                }
            }
        }

        $this->tests  = \array_merge($this->tests, $that->getTests());
        $this->report = null;
    }

    public function setCacheTokens(bool $flag): void
    {
        $this->cacheTokens = $flag;
    }

    public function getCacheTokens(): bool
    {
        return $this->cacheTokens;
    }

    public function setCheckForUnintentionallyCoveredCode(bool $flag): void
    {
        $this->checkForUnintentionallyCoveredCode = $flag;
    }

    public function setForceCoversAnnotation(bool $flag): void
    {
        $this->forceCoversAnnotation = $flag;
    }

    public function setCheckForMissingCoversAnnotation(bool $flag): void
    {
        $this->checkForMissingCoversAnnotation = $flag;
    }

    public function setCheckForUnexecutedCoveredCode(bool $flag): void
    {
        $this->checkForUnexecutedCoveredCode = $flag;
    }

    public function setAddUncoveredFilesFromWhitelist(bool $flag): void
    {
        $this->addUncoveredFilesFromWhitelist = $flag;
    }

    public function setProcessUncoveredFilesFromWhitelist(bool $flag): void
    {
        $this->processUncoveredFilesFromWhitelist = $flag;
    }

    public function setDisableIgnoredLines(bool $flag): void
    {
        $this->disableIgnoredLines = $flag;
    }

    public function setIgnoreDeprecatedCode(bool $flag): void
    {
        $this->ignoreDeprecatedCode = $flag;
    }

    public function setUnintentionallyCoveredSubclassesWhitelist(array $whitelist): void
    {
        $this->unintentionallyCoveredSubclassesWhitelist = $whitelist;
    }

    /**
     * Determine the priority for a line
     *
     * 1 = the line is not set
     * 2 = the line has not been tested
     * 3 = the line is dead code
     * 4 = the line has been tested
     *
     * During a merge, a higher number is better.
     *
     * @param array $data
     * @param int   $line
     *
     * @return int
     */
    private function getLinePriority($data, $line)
    {
        if (!\array_key_exists($line, $data)) {
            return 1;
        }

        if (\is_array($data[$line]) && \count($data[$line]) === 0) {
            return 2;
        }

        if ($data[$line] === null) {
            return 3;
        }

        return