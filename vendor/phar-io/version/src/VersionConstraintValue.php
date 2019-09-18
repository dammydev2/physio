strlen($arguments) > 0) {
            $arguments = explode(',', $arguments);
            foreach ($arguments as &$argument) {
                $argument = explode(' ', self::stripRestArg(trim($argument)), 2);
                if ($argument[0][0] === '$') {
                    $argumentName = substr($argument[0], 1);
                    $argumentType = new Void_();
                } else {
                    $argumentType = $typeResolver->resolve($argument[0], $context);
                    $argumentName = '';
                    if (isset($argument[1])) {
                        $argument[1] = self::stripRestArg($argument[1]);
                        $argumentName = substr($argument[1], 1);
                    }
                }

                $argument = [ 'name' => $argumentName, 'type' => $argumentType];
            }
        } else {
            $arguments = [];
        }

        return new static($methodName, $arguments, $returnType, $static, $description);
    }

    /**
     * Retrieves the method name.
     *
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @return string[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Checks whether the method tag describes a static method or not.
     *
     * @return bool TRUE if the method declaration is for a static method, FALSE otherwise.
     */
    public function isStatic()
    {
        return $this->isStatic;
    }

    /**
     * @return Type
     */
    public function getReturnType()
    {
        return $this->returnType;
    }

    public function __toString()
    {
        $arguments = [];
        foreach ($this->arguments as $argument) {
            $arguments[] = $argument['type'] . ' $' . $argument['name'];
        }

        return trim(($this->isStatic() ? 'static ' : '')
            . (string)$this->returnType . ' '
            . $this->methodName
            . '(' . implode(', ', $arguments) . ')'
            . ($this->description ? ' ' . $this->description->render() : ''));
    }

    private function filterArguments($arguments)
    {
        foreach ($arguments as &$argument) {
            if (is_string($argument)) {
                $argument = [ 'name' => $argument ];
            }

            if (! isset($argument['type'])) {
                $argument['type'] = new Void_();
            }

            $keys = array_keys($argument);
            sort($keys);
            if ($keys !== [ 'name', 'type' ]) {
                