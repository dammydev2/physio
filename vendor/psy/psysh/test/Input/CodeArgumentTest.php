he builder configured for this environment
     *
     * @return UuidBuilderInterface
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Returns the UUID UUID coder-decoder configured for this environment
     *
     * @return CodecInterface
     */
    public function getCodec()
    {
        return $this->codec;
    }

    /**
     * Returns the system node ID provider configured for this environment
     *
     * @return NodeProviderInterface
     */
    public function getNodeProvider()
    {
        return $this->nodeProvider;
    }

    /**
     * Returns the number converter configured for this environment
     *
     * @return NumberConverterInterface
     */
    public function getNumberConverter()
    {
        return $this->numberConverter;
    }

    /**
     * Returns the random UUID generator configured for this environment
     *
     * @return RandomGeneratorInterface
     */
    public function getRandomGenerator()
    {
        return $this->randomGenerator;
    }

    /**
     * Returns the time-based UUID generator configured for this environment
     *
     * @return TimeGeneratorInterface
     */
  