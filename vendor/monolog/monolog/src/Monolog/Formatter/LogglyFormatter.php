  $message = $record['message'];
        if ($record['context']) {
            $message .= ' ' . json_encode($this->connector->getDumper()->dump(array_filter($record['context'])));
        }
        $this->connector->getDebugDispatcher()->dispatchDebug($message, $tags, $this->options['classesPartialsTraceIgnore']);
    }

    private function handleExceptionRecord(array $record)
    {
        $this->connector->getErrorsDispatcher()->dispatchException($record['context']['exception']);
    }

    private function handleErrorRecord(array $record)
    {
        $context = $record['context'];

        $this->connector->getErrorsDispatcher()->dispatchError(
            isset($context['code']) ? $context['code'] : null,
            isset($context['message']) ? $context['message'] : $record['message'],
            isset($context['file']) ? $context['file'] : null,
            isset($context['line']) ? $context['line'] : null,
            $this->options['classesPartialsTraceIgnore']
        );
    }

    private function getRecordTags(array &$record)
    {
        $tags = null;
        if (!empty($record['context'])) {
            $context = & $record['context'];
            foreach ($this->options['debugTagsKeysInContext'] as $key) {
                if (!empty($context[$key])) {
                    $tags = $c