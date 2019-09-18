ition(), ['context' => $this->context, 'request' => $this->request ?: $this->createRequest($pathinfo)])) {
            return [self::REQUIREMENT_MISMATCH, null];
        }

        return [self::REQUIREMENT_MATCH, null];
    }

    /**
     * Get merged default parameters.
     *
     * @param array $params   The parameters
     * @param array $defaults The defaults
     *
     * @return array Merged default parameters
     */
    protected function mergeDefaults($params, $defaults)
    {
        foreach ($params as $key => $value) {
            if (!\is_int($key) && null !== $value) {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }

    protected function getExpressionLanguage()
    {
        if (null === $this->expre