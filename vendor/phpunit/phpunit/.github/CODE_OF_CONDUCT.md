houldStop(): bool
    {
        return $this->stop;
    }

    /**
     * Marks that the test run should stop.
     */
    public function stop(): void
    {
        $this->stop = true;
    }

    /**
     * Returns the code coverage object.
     */
    public function getCodeCoverage(): ?CodeCoverage
    {
        return $this->codeCoverage;
    }

    /**
     * Sets the code coverage object.
     */
    public function setCodeCoverage(CodeCoverage $codeCoverage): void
    {
        $this->codeCoverage = $codeCoverage;
    }

    /**
     * Enables or disables the error-to-exception conversion.
     */
    public function convertErrorsToExceptions(bool $flag): void
    {
        $this->convertErrorsToExceptions = $flag;
    }

    /**
     * Returns the error-to-exception conversion setting.
     */
    public function getConvertErrorsToExceptions(): bool
    {
        return $this->convertErrorsToExceptions;
    }

    /**
     * Enables or disables the stopping when an error occurs.
     */
    public function stopOnError(bool $flag): void
    {
        $this->stopOnError = $flag;
    }

    /**
     * Enables or disables the stopping when a failure occurs.
     */
    public function stopOnFailure(bool $flag): void
    {
        $this->stopOnFailure = $flag;
    }

    /**
     * Enables or disables the stopping when a warning occurs.
     */
    public function stopOnWarning(bool $flag): void
    {
        $this->stopOnWarning = $flag;
    }

    public function beStrictAboutTestsThatDoNotTestAnything(bool $flag): void
    {
        $this->beStrictAboutTestsThatDoNotTestAnything = $flag;
    }

    public function isStrictAboutTestsThatDoNotTestAnything(): bool
    {
        return $this->beStrictAboutTestsThatDoNotTestAnything;
    }

    public function beStrictAboutOutputDuringTests(bool $flag): void
    {
        $this->beStrictAboutOutputDuringTests = $flag;
    }

    public function isStrictAboutOutputDuringTests(): bool
    {
        return $this->beStrictAboutOutputDuringTests;
    }

    public function beStrictAboutResourceUsageDuringSmallTests(bool $flag): void
    {
        $this->beStrictAboutResourceUsageDuringSmallTests = $flag;
    }

    public function isStrictAboutResourceUsageDuringSmallTests(): bool
    {
        return $this->beStrictAboutResourceUsageDuringSmallTests;
    }

    public function enforceTimeLimit(bool $flag)