 => $level,
            'level_name' => $levelName,
            'channel' => $this->name,
            'datetime' => $ts,
            'extra' => array(),
        );

        try {
            foreach ($this->processors as $processor) {
                $record = call_user_func($processor, $record);
            }

            while ($handler = current($this->handlers)) {
                if (true === $handler->handle($record)) {
                    break;
                }

                next($this->handlers);
            }
        } catch (Exception $e) {
            $this->handleException($e, $record);
        }

        return true;
    }

    /**
     * Ends a log cycle and frees all resources used by handlers.
     *
     * Closing a Handler means flushing all buffers and freeing any open resources/handles.
     * Handlers that have been closed should be able to accept log records again and re-open
     * themselves on demand, 