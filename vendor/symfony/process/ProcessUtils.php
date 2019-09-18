ns An array of options
     *
     * @throws \InvalidArgumentException When unsupported option is provided
     */
    public function setOptions(array $options)
    {
        $this->options = [
            'cache_dir' => null,
            'debug' => false,
            'generator_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator',
            'generator_base_class' => 'Symfony\\Component\\Routing\\Generator\\UrlGenerator',
            'generator_dumper_class' => 'Symfony\\Component\\Routing\\Generator\\Dumper\\PhpGeneratorDumper',
            'generator_cache_class' => 'ProjectUrlGenerator',
            'matcher_class' => 'Symfony\\Component\\Routing\\Matcher\\UrlMatcher',
            'matcher_base_class' => 'Symfony\\Component\\Routing\\Matcher\\UrlMatcher',
            'matcher_dumper_class' => 'Symfony\\Component\\Routing\\Matcher\\Dumper\\PhpMatcherDumper',
            'matcher_cache_class' => 'ProjectUrlMatcher',
            'resource_type' => null,
            'strict_requirements' => true,
        ];

        // check option names and live merge, if errors are encountered Exception will be thrown
        $invalid = [];
        foreach ($options as $key => $value) {
            if (\array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            } else {
                $invalid[] = $key;
            }
        }

        if ($invalid) {
            throw new \InvalidArgumentException(sprintf('The Router does not support the following options: "%s".', implode('", "', $invalid)));
        }
    }

    /**
     * Sets an option.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     *
     * @throws \InvalidArgumentException
     */
    public function setOption($key, $value)
    {
        if (!\array_key_exists($key, $this->options)) {
        