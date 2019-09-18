xception with or without its own stack trace based on
     * `includeStacktraces` property.
     *
     * @param Exception|Throwable $e
     *
     * @return array
     */
    protected function normalizeException($e)
    {
        // TODO 2.0 only check for Throwable
        if (!$e instanceof Exception && !$e instanceof Throwable) {
            throw new \InvalidArgumentException('Exception/Throwable expected, got '.gettype($e).' / '.Utils::getClass($e));
        }

        $data = array(
            'class' => Utils::getClass($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile().':'.$e->getLine(),
        );

        if ($this->includeStacktraces) {
            $trace = $e->getTrace();
            foreach ($trace as $frame) {
                if (isset($frame['file'])) {
                    $data['trace'][] = $frame['file'].':'.$frame['line'];
                } elseif 