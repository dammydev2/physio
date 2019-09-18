umTestedMethods(),
            $this->getNumMethods(),
            $asString
        );
    }

    /**
     * Returns the percentage of functions and methods that has been tested.
     *
     * @return int|string
     */
    public function getTestedFunctionsAndMethodsPercent(bool $asString = true)
    {
        return Util::percent(
            $this->getNumTestedFunctionsAndMethods(),
            $this->getNumFunctionsAndMethods(),
            $asString
        );
    }

    /**
     * Returns the percentage of executed lines.
     *
     * @return int|string
     */
    public function getLineExecutedPercent(bool $asString = true)
    {
        return Util::percent(
            $this->getNumExecutedLines(),
            $this->getNumExecutableLines(),
            