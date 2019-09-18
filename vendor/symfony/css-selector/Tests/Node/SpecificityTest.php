atalErrorException) {
                if ($exception instanceof FatalThrowableError) {
                    $error = [
                        'type' => $type,
                        'message' => $message,
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                    ];
                } else {
                    $message = 'Fatal '.$message;
                }
            } elseif ($exception instanceof \ErrorException) {
                $message = 'Uncaught '.$message;
            } else {
                $message = 'Uncaught Exception: '.$message;
            }
        }
        if ($this->loggedErrors & $type) {
            try {
                $this->loggers[$type][0]->log($this->loggers[$type][1], $message, ['exception' => $exception]);
            } catch (\Throwable $handlerException) {
            }
        }
        if ($exception instanceof FatalErrorException && !$exception instanceof OutOfMemoryException && $error) {
            foreach ($this->getFatalErrorHandlers() as $handler) {
                if ($e = $handler->handleError($error, $exception)) {
                    $exception = $e;
                    break;
                }
            }
        }
        $exceptionHandler = $this->exceptionHandler;
        $this->exceptionHandler = null;
        try {
            if (null !== $exceptionHandler) {
                return $exceptionHandler($exception);
            }
            $handlerException = $handlerException ?: $exception;
        } catch (\Throwable $handlerException) {
        }
        if ($exception === $handlerException) {
            self::$reservedMemory = null; // Disable the fatal error handler
            throw $exception; // Give back $exception to the native handler
        }
        $this->handleException($handlerException);
    }

    /**
     * Shutdown registered function for handling PHP fatal errors.
     *
     * @param array $error An array as returned by error_get_last()
     *
     * @internal
     */
    public static function handleFatalE