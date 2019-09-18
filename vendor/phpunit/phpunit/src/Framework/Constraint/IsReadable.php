        $generator = new Generator;

                return $generator->getMock($this->returnType, [], [], '', false);
        }
    }

    public function setProxiedCall(): void
    {
        $this->proxiedCall = true;
    }

    public function toString(): string
    {
        $exporter = new Exporter;

        return \sprintf(
            '%s::%s(%s)%s',
            $this->className,
            $this->methodName,
            \implode(
                ', ',
                \array_map(
                    [$exporter, 'shortenedExport'],
                    $this->parameters
                )
            ),
            $this->returnType ? \sprintf(': %s', $this->returnType) : ''
        );
    }

    /**
     * @param object $original
     *
     * @return object
     */
    private function cloneObject($original)
    {
        $cloneable = null;
        $object    = new ReflectionObject($original);

        // Check the blacklist before asking PHP reflection to work around
        // https://bugs.php.net/bug.php?id=53967
        if ($object->isInternal() &&
            isset(self::$uncloneableExtensions[$object->getExtensionName()])) {
            $cloneable = false;
        }

        if ($cloneable === null) {
            foreach (self::$uncloneableClasses as $class) {
                if ($original instanceof $class) {
    