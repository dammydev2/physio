trait) {
                foreach ($trait['methods'] as $method) {
                    if ($method['executableLines'] > 0) {
                        $this->numTraits++;

                        continue 2;
                    }
                }
            }
        }

        return $this->numTraits;
    }

    /**
     * Returns the number of tested traits.
     */
    public function getNumTestedTraits(): int
    {
        return $this->numTestedTraits;
    }

    /**
     * Returns the number of methods.
     */
    public function getNumMethods(): int
    {
        if ($this->numMethods === null) {
            $this->numMethods = 0;

            foreach ($this->classes as $class) {
                foreach ($class['method