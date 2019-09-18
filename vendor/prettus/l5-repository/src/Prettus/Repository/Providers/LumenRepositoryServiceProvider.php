s->returnValue;
    }

    /**
     * Set the most recent Exception.
     *
     * @param \Exception $e
     */
    public function setLastException(\Exception $e)
    {
        $this->lastException = $e;
    }

    /**
     * Get the most recent Exception.
     *
     * @throws \InvalidArgumentException If no Exception has been caught
     *
     * @return null|\Exception
     */
    public function getLastException()
    {
        if (!isset($this->lastException)) {
            throw new \InvalidArgumentException('No most-recent exception');
        }

        return $this->lastException;
    }

    /**
     * Set the most recent output from evaluated code.
     *
     * @param string $lastStdout
     */
    public function setLastStdout($lastStdout)
    {
        $this->lastStdout = $lastStdout;
    }

    /**
     * Get the most recent outp