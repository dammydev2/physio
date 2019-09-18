       }

        return \implode(PHP_EOL, $messages);
    }

    /**
     * Format options as text.
     *
     * @return string
     */
    private function optionsAsText()
    {
        $max = $this->getMaxWidth();
        $messages = [];

        $options = $this->getOptions();
        if ($options) {
            $messages[] = '<comment>Options:</comment>';

            foreach ($options as $option) {
                if ($option->acceptValue() && null !== $option->getDefault() && (!\is_array($option->getDefault()) || \count($option->getDefault()))) {
                    $default = \sprintf('<comment> (default: %s)</comment>', $this->formatDefaultValue($option->getDefault()));
                } else {
                    $default = '';
                }

                $multiple = $option->isArray() ? '<comment> (multiple values allowed)</comment>' : '';
                $description = \str_replace("\n", "\n" . \str_pad('', $max + 2, ' '), $option->getDescription());

                $optionMax = $max - \strlen($option->getName()) - 2;
                $messages[] = \sprintf(
                    " <info>%s</info> %-${optionMax}s%s%s%s",
                    '--' . $option->getName(),
                    $option->getShortcut() ? \sprintf('(-%s) ', $option->getShortcut()) : '',
                    $description,
                    $default,
                    $multiple
                );
            }

            $messages[] = '';
        }

        return \implode(PHP_EOL, $messages);
    }

    /**
     * Calculate the maximum padding width for a set of lines.
     *
     * @return int
     */
    private function getMaxWidth()
    {
        $max = 0;

        foreach ($this->getOptions() as $option) {
            $nameLength = \strlen($option->getName()) + 2;
            if ($option->getShortcut()) {
                $nameLength += \strlen($option->getShortcut()) + 3;
            }

            $max = \max($max, $nameLength);
        }

        foreach ($this->getArguments() as $argument) {
            $max = \max($max, \strlen($argument->getName()));
        }

        return ++$max;
    }

    /**
     * Format an option default as text.
     *
     * @param mixed $default
     *
     * @return string
     */
    private function formatDefaultValue($default)
    {
  