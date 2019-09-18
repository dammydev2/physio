($this->traits === null) {
            $this->traits = [];

            foreach ($this->children as $child) {
                $this->traits = \array_merge(
                    $this->traits,
                    $child->getTraits()
                );
            }
        }

        return $this->traits;
    }

    /**
     * Returns the functions of this node.
     */
    public function getFunctions(): array
    {
        if ($this->functions === null) {
            $this->functions = [];

            foreach ($this->children as $child) {
                $this->functions = \array_merge(
                    $this->functions,
                    $child->getFunctions()
                );
            }
        }

        return $this->functions;
    }

    /**
     * Returns the LOC/CLOC/NCLOC of this node.
     */
    public function getLinesOfCode(): array
    {
        if ($this->linesOfCode === null) {
            $this->linesOfCode = ['loc' => 0, 'cloc' => 0, 'ncloc' => 0];

            foreach ($this->children as $child) {
                $linesOfCode = $child->getLinesOfCode();

                $this->linesOfCode['loc'] += $linesOfCode['loc'];
                $this->linesOfCode['cloc'] += $linesOfCode['cloc'];
                $this->linesOfCode['ncloc'] += $linesOfCode['ncloc'];
            }
        }

        return $this->linesOfCode;
    }

    /**
     * Returns the number of executable lines.
     */
    public function getNumExecutableLines(): int
    {
        if ($this->numExecutableLines === -1) {
            $this->numExecutableLines = 0;

            foreach ($this->children as $child) {
                $this->numExecutableLines += $child->getNumExecutableLines();
            }
        }

        return $this->numExecutableLines;
    }

    /**
     * Returns the number of executed lines.
     */
    public function getNumExecutedLines(): int
    {
        if ($th